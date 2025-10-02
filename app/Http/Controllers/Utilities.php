<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ShopeeOrdersImport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Models\Profile;
use App\Models\Conversion;

class Utilities extends Controller
{
  const SHOPEE_CAMPAIGN_ID = 40;

  public function view() {
    return view('content.utilities.index');
  }

  public function shopeeUpload(Request $request) {
    // dd($request->file('file'));
    $array = Excel::toArray(new ShopeeOrdersImport(), $request->file('file'));

    $insertData = [];

    foreach ($array as $sheet) {
      foreach ($sheet as $key => $row) {
        $pubRate = 0.8;
        $sysRate = 0.2;

        $affiliateId = isset($row['sub_id1']) ? $row['sub_id1'] : '';

        $userId = Profile::where('affiliate_id', $affiliateId)->pluck('user_id')->first();
        if (!$userId) {
          continue;
        }
        $orderCode = $row['id_don_hang'];
        $time = Carbon::parse($row['thoi_gian_dat_hang']);
        $sales = $row['giad'];
        $quantity = $row['so_luong'];
        $sumcom = $row['tong_hoa_hong_san_phamd'];
        $status = 'Pending';

        $commissionPub = $sumcom * $pubRate;
        $commissionSys = $sumcom * $sysRate;

        $productCode = $row['item_id'].'_'.$row['id_model'].($row['promotion_id'] ? '_'.$row['promotion_id'] : '');
        $productName = $row['ten_item'].' | '.$row['ten_shop'];
        $campaginId = self::SHOPEE_CAMPAIGN_ID;

        $insertData[] = [
          'code' => sha1(time() + $key),
          'order_code' => $orderCode,
          'order_time' => $time,
          'unit_price' => $sales,
          'quantity' => $quantity,
          'commission_pub' => $commissionPub,
          'commission_sys' => $commissionSys,
          'status' => $status,
          'product_code' => $productCode,
          'product_name' => $productName,
          'campaign_id' => $campaginId,
          'click_id' => 47600,
          'user_id' => $userId,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
          'comment' => null
        ];
      }
    }

    try {
      foreach ($insertData as $item) {
        Conversion::upsert(
          [
            $item
          ],
          [
            'campaign_id',
            'order_code',
            'product_code',
          ],
          [
            'unit_price',
            'quantity',
            'commission_pub',
            'commission_sys',
            'updated_at',
          ]
        );
      }
      // Conversion::insert($insertData);

      // Conversion::removeDup(
      //   $campaginId,
      //   Carbon::now()->subDays(120)->format('Y-m-d'),
      //   Carbon::now()->format('Y-m-d')
      // );

      return response()->json([
        'status' => 200,
        'message' => 'succcess'
      ], 200);
    } catch (\Throwable $th) {
      return response()->json([
        'status' => 400,
        'message' => $th
      ], 400);
    }
  }
}
