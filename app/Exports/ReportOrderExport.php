<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Conversion;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportOrderExport implements FromCollection, WithHeadings
{
  /**
  * @return \Illuminate\Support\Collection
  */

  protected $request;

  public function __construct(Request $request)
  {
      $this->request = $request;
  }

  public function headings(): array
    {
      return [
        'ID chuyển đổi',
        'Thời gian phát sinh',
        'Thời gian click',
        'Chiến dịch',
        'Tên tài khoản',
        'Affiliate ID',
        'Email',
        'ID đơn hàng',
        'Mã sản phẩm',
        'Tên sản phẩm',
        'Giá trị đơn hàng(₫)',
        'Số lượng',
        'Hoa hồng Pub(₫)',
        'Hoa hồng TK(₫)',
        'Hoa hồng tổng(₫)',
        'Trạng thái',
        'Sub ID 1',
        'Sub ID 2',
        'Sub ID 3',
        'Sub ID 4',
      ];
    }

  public function collection()
  {
    if (!$this->request->date) {
      $this->request->merge(['date' => Carbon::now()->subDays(self::DEFAULT_SUB_DAYS)->format('Y-m-d')." - ".Carbon::now()->format('Y-m-d')]);
    }

    $data = Conversion::query()
    ->join('profiles', 'conversions.user_id', '=', 'profiles.user_id')
    ->when($this->request->date, function($q, $date) {
      $dateArray = explode(" - ", $date);
      $q->whereBetween('order_time', [$dateArray[0].' 00:00:00', $dateArray[1].' 23:59:59']);
    })
    ->when($this->request->keyword, function($q, $keyword) {
      return $q->whereHas('campaign', function ($query) use ($keyword) {
        $query->where('name', 'like', '%'.$keyword.'%');
      });
    })
    ->when($this->request->status, function($q, $status) {
      $q->where('status', $status);
    })
    ->when($this->request->affiliate_id, function($q, $affiliateId) {
      $q->where('affiliate_id', $affiliateId);
    })
    ->when($this->request->order_code, function($q, $orderCode) {
      $q->where('order_code', $orderCode);
    })
    ->when($this->request->product_code, function($q, $productCode) {
      $q->where('product_code', $productCode);
    })
    ->when($this->request->product_name, function($q, $productName) {
      $q->where('product_name', 'like', '%'.$productName.'%');
    })
    ->when($this->request->sub1, function($q, $sub1) {
      return $q->whereHas('click', function ($query) use ($sub1) {
        return $query->whereHas('linkHistory', function ($qr) use ($sub1) {
          $qr->where('sub1', 'like', '%'.$sub1.'%');
        });
      });
    })
    ->when($this->request->sub2, function($q, $sub2) {
      return $q->whereHas('click', function ($query) use ($sub2) {
        return $query->whereHas('linkHistory', function ($qr) use ($sub2) {
          $qr->where('sub2', 'like', '%'.$sub2.'%');
        });
      });
    })
    ->when($this->request->sub3, function($q, $sub3) {
      return $q->whereHas('click', function ($query) use ($sub3) {
        return $query->whereHas('linkHistory', function ($qr) use ($sub3) {
          $qr->where('sub3', 'like', '%'.$sub3.'%');
        });
      });
    })
    ->when($this->request->sub4, function($q, $sub4) {
      return $q->whereHas('click', function ($query) use ($sub4) {
        return $query->whereHas('linkHistory', function ($qr) use ($sub4) {
          $qr->where('sub4', 'like', '%'.$sub4.'%');
        });
      });
    })
    ->orderBy('order_time', 'desc')
    ->get();

    $results = [];

    foreach ($data as $item) {
      $results[] = [
        'code' => $item->code,
        'order_time' => $item->order_time,
        'click_time' => $item->click->created_at,
        'campaign' => $item->campaign->name,
        'user_name' => $item->user->name,
        'affiliate_id' => $item->user->profile->affiliate_id,
        'user_email' => $item->user->email,
        'order_code' => $item->order_code,
        'product_code' => $item->product_code,
        'product_name' => $item->product_name,
        'unit_price' => $item->unit_price,
        'quantity' => $item->quantity,
        'commission_pub' => $item->commission_pub,
        'commission_sys' => $item->commission_sys,
        'commission_all' => $item->commission_pub + $item->commission_sys,
        'status' => $item->status,
        'sub1' => $item->click->linkHistory->sub1,
        'sub2' => $item->click->linkHistory->sub2,
        'sub3' => $item->click->linkHistory->sub3,
        'sub4' => $item->click->linkHistory->sub4,
      ];
    }

    return collect($results);
  }
}
