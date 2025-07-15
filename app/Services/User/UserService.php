<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserService
{

  public function getUser()
  {
    try {
      return User::query()
      ->join('profiles', 'users.id', '=', 'profiles.user_id')
      ->whereNotNull('profiles.affiliate_id')
      ->whereNotNull('users.email_verified_at')
      ->get();
    } catch (\Throwable $th) {
      Log::error('Lỗi xảy ra khi select user: ' . $th->getMessage());
    }
  }

  public function getUserById($id)
  {
    try {
      return User::query()
      ->join('profiles', 'users.id', '=', 'profiles.user_id')
      ->whereNotNull('profiles.affiliate_id')
      ->whereNotNull('users.email_verified_at')
      ->where('users.id', '=', $id)
      ->first();
    } catch (\Throwable $th) {
      Log::error('Lỗi xảy ra khi select user by id: ' . $th->getMessage());
    }
  }

  public function getPayable($id) {
    try {
      // return Transaction::query()
      // ->where('user_id', $id)
      // ->groupBy('target_month')
      // ->get();
      $sql = "
      SELECT target_month
        ,DATE_FORMAT(min(submission_date), '%Y-%m-%d') submission_date
        ,DATE_FORMAT(min(processing_date), '%Y-%m-%d') processing_date
        ,sum(amount_pub) amount_pub
        ,sum(amount) amount
        ,SUM(paid) paid
        ,SUM(advance) advance
      FROM (
        SELECT target_month
          ,NULL submission_date
          ,NULL processing_date
          ,amount_pub
          ,0 amount
          ,0 paid
          ,0 advance
        FROM transactions
        WHERE user_id = $id
        
        UNION ALL
        
        SELECT DATE_FORMAT(submission_date, '%Y-%m') target_month
          ,submission_date
          ,NULL processing_date
          ,0 amount_pub
          ,amount
          ,0 paid
          ,0 advance
        FROM payment_requests
        WHERE user_id = $id
        
        UNION ALL
        
        SELECT DATE_FORMAT(submission_date, '%Y-%m') target_month
          ,submission_date
          ,processing_date
          ,0 amount_pub
          ,0 amount
          ,amount AS paid
          ,0 advance
        FROM payment_requests
        WHERE user_id = $id
          AND processing_date IS NOT NULL
          AND STATUS = 0
        
        UNION ALL
        
        SELECT target_month
          ,NULL submission_date
          ,NULL processing_date
          ,0 amount_pub
          ,0 amount
          ,0 paid
          ,amount AS advance
        FROM advance_payment_histories
        WHERE user_id = $id
        ) AS combined
      GROUP BY target_month
      ";

      return DB::select($sql);

    } catch (\Throwable $th) {
      Log::error('Lỗi xảy ra khi select user by id: ' . $th->getMessage());
    }
  }

  public function uploadIdImage($request) {
    $client = curl_init();

    $fileFrontPath = $request->file('file_front')->getPathname();
    $fileBackPath = $request->file('file_back')->getPathname();
    $userId = $request->user_id;

    $postData = [
      'file_front' => new \CURLFile($fileFrontPath, $request->file('file_front')->getMimeType(), $request->file('file_front')->getClientOriginalName()),
      'file_back' => new \CURLFile($fileBackPath, $request->file('file_back')->getMimeType(), $request->file('file_back')->getClientOriginalName()),
    ];

    curl_setopt_array($client, [
      CURLOPT_URL => 'https://tkglobal.asia/api/uploadImage',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => $postData,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      // Nếu cần thêm header:
      CURLOPT_HTTPHEADER => ['Accept: application/json'],
    ]);

    $response = curl_exec($client);
    $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
    curl_close($client);

    // Xử lý kết quả
    if ($httpCode == 200) {
      $data = json_decode($response, TRUE);

      $profle = Profile::where('user_id', $userId)->first();

      $profle->id_img_front = $data['front']['path'];
      $profle->id_img_back = $data['back']['path'];
      $profle->save();

      return true;
    }

    return false;
  }
}
