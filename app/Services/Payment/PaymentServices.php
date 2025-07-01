<?php

namespace App\Services\Payment;

use App\Models\User;
use App\Models\PaymentRequest as PaymentRequestModel;
use App\Models\Profile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentServices
{

  public function addRequest($request)
  {
    try {
      $affiliate_id = $request->affiliate_id;

      $profile = Profile::query()
      ->where('affiliate_id', $affiliate_id)
      ->first();
      $userId = $profile->user_id;

      PaymentRequestModel::create([
        'code' => sha1(time()),
        'submission_date' => $request->month ? $request->month . '-01 00:00:00' : Carbon::now(),
        'amount' => $request->amount,
        'comment' => $request->comment,
        'user_id' => $userId
      ]);
    } catch (\Throwable $th) {
      Log::error('Lỗi xảy ra khi select user: ' . $th->getMessage());
    }
  }
}
