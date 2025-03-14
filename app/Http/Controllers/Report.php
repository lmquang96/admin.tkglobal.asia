<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversion;
use App\Models\Click;
use App\Models\Campaign;
use Carbon\Carbon;
use App\Exports\ReportOrderExport;
use Maatwebsite\Excel\Facades\Excel;

class Report extends Controller
{
  const PER_PAGE = 50;
  const DEFAULT_SUB_DAYS = 7;

  public function performance(Request $request)
  {
    $group = $request->group;
    if (empty($group)) {
      $group = 'campaign_id';
    }
    $groupSelect = '';
    if ($group == 'campaign_id') {
      $groupSelect = 'campaign_id';
    } else if ($group == 'user_id') {
      $groupSelect = 'user_id';
    } else if ($group == 'order_time') {
      $groupSelect = "date_format(order_time, '%Y-%m-%d') date";
    }
    if (!$request->date) {
      $request->merge(['date' => Carbon::now()->subDays(self::DEFAULT_SUB_DAYS)->format('Y-m-d')." - ".Carbon::now()->format('Y-m-d')]);
    }
    $data = Conversion::with('campaign')
    ->when($request->date, function($q, $date) {
      $dateArray = explode(" - ", $date);
      $q->whereBetween('order_time', [$dateArray[0].' 00:00:00', $dateArray[1].' 23:59:59']);
    })
    ->when($request->keyword, function($q, $keyword) use ($group) {
      if ($group == 'campaign_id') {
        return $q->whereHas('campaign', function ($query) use ($keyword) {
          $query->where('name', 'like', '%'.$keyword.'%');
        });
      } else if ($group == 'user_id') {
        return $q->whereHas('user', function ($query) use ($keyword) {
          $query->where('name', 'like', '%'.$keyword.'%');
        });
      }
    })
    ->selectRaw($groupSelect.', count(*) cnt, SUM(unit_price) as total_price, SUM(commission_pub) as total_com')
    ->groupBy($group == 'order_time' ? 'date' : $groupSelect);

    $totalConversion = $data->get()->sum(function ($item) {
      return $item->cnt;
    });

    $totalPrice = $data->get()->sum(function ($item) {
      return $item->total_price;
    });

    $totalCom = $data->get()->sum(function ($item) {
      return $item->total_com;
    });

    $data = $data->paginate(self::PER_PAGE)->withQueryString();

    if ($group == 'order_time') {
      $groupSelect = "date_format(clicks.created_at, '%Y-%m-%d') date";
    }

    $clicks = Click::query()
    ->join('link_histories', 'link_histories.id', '=', 'clicks.link_history_id')
    ->when($group == 'user_id', function($q) {
      $q->join('users', 'link_histories.user_id', '=', 'users.id');
    })
    ->when($request->date, function($q, $date) {
      $dateArray = explode(" - ", $date);
      $q->whereBetween('clicks.created_at', [$dateArray[0].' 00:00:00', $dateArray[1].' 23:59:59']);
    })
    ->when($request->keyword, function($q, $keyword) {
      return $q->join('campaigns', 'campaigns.id', '=', 'link_histories.campaign_id')
      ->where('name', 'like', '%'.$keyword.'%');
    })
    ->where('link_histories.user_id', '!=', 8)
    ->selectRaw($groupSelect.', count(*) cnt')
    ->groupBy($group == 'order_time' ? 'date' : $groupSelect);

    $clickCount = $clicks->get()->sum(function ($item) {
      return $item->cnt;
    });

    $clicks = $clicks->get();

    $clicks = $clicks->keyBy($group == 'order_time' ? 'date' : $group)->toArray();

    return view('content.reports.performance', compact('data', 'clicks', 'totalConversion', 'clickCount', 'totalPrice', 'totalCom'));
  }

  public function order(Request $request) {
    if (!$request->date) {
      $request->merge(['date' => Carbon::now()->subDays(self::DEFAULT_SUB_DAYS)->format('Y-m-d')." - ".Carbon::now()->format('Y-m-d')]);
    }

    if ($request->group == 'campaign_id') {
      $request->keyword = $request->groupValue;
    }

    $data = Conversion::query()
    ->join('profiles', 'conversions.user_id', '=', 'profiles.user_id')
    ->when($request->date, function($q, $date) {
      $dateArray = explode(" - ", $date);
      $q->whereBetween('order_time', [$dateArray[0].' 00:00:00', $dateArray[1].' 23:59:59']);
    })
    ->when($request->keyword, function($q, $keyword) {
      return $q->whereHas('campaign', function ($query) use ($keyword) {
        $query->where('name', 'like', '%'.$keyword.'%');
      });
    })
    ->when($request->status, function($q, $status) {
      $q->where('status', $status);
    })
    ->when($request->affiliate_id, function($q, $affiliateId) {
      $q->where('affiliate_id', $affiliateId);
    })
    ->when($request->order_code, function($q, $orderCode) {
      $q->where('order_code', $orderCode);
    })
    ->when($request->product_code, function($q, $productCode) {
      $q->where('product_code', $productCode);
    })
    ->when($request->product_name, function($q, $productName) {
      $q->where('product_name', 'like', '%'.$productName.'%');
    })
    ->when($request->sub1, function($q, $sub1) {
      return $q->whereHas('click', function ($query) use ($sub1) {
        return $query->whereHas('linkHistory', function ($qr) use ($sub1) {
          $qr->where('sub1', 'like', '%'.$sub1.'%');
        });
      });
    })
    ->when($request->sub2, function($q, $sub2) {
      return $q->whereHas('click', function ($query) use ($sub2) {
        return $query->whereHas('linkHistory', function ($qr) use ($sub2) {
          $qr->where('sub2', 'like', '%'.$sub2.'%');
        });
      });
    })
    ->when($request->sub3, function($q, $sub3) {
      return $q->whereHas('click', function ($query) use ($sub3) {
        return $query->whereHas('linkHistory', function ($qr) use ($sub3) {
          $qr->where('sub3', 'like', '%'.$sub3.'%');
        });
      });
    })
    ->when($request->sub4, function($q, $sub4) {
      return $q->whereHas('click', function ($query) use ($sub4) {
        return $query->whereHas('linkHistory', function ($qr) use ($sub4) {
          $qr->where('sub4', 'like', '%'.$sub4.'%');
        });
      });
    });

    // dd($data->get()->toArray());

    $totalConversion = $data->count();

    $totalPrice = $data->get()->sum(function ($item) {
      return $item->quantity * $item->unit_price;
    });

    $totalCom = $data->get()->sum(function ($item) {
      return $item->quantity * $item->commission_pub;
    });

    $totalComSys = $data->get()->sum(function ($item) {
      return $item->quantity * $item->commission_sys;
    });

    $data = $data->orderBy('order_time', 'desc')->paginate(self::PER_PAGE)->withQueryString();

    $clickCount = Click::query()
    ->join('link_histories', 'link_histories.id', '=', 'clicks.link_history_id')
    ->when($request->date, function($q, $date) {
      $dateArray = explode(" - ", $date);
      $q->whereBetween('clicks.created_at', [$dateArray[0].' 00:00:00', $dateArray[1].' 23:59:59']);
    })
    ->when($request->keyword, function($q, $keyword) {
      return $q->join('campaigns', 'campaigns.id', '=', 'link_histories.campaign_id')
      ->where('name', 'like', '%'.$keyword.'%');
    })
    ->count();

    $campaigns = Campaign::where('status', 1)->get();

    return view('content.reports.order', compact('data', 'totalPrice', 'totalCom', 'totalComSys', 'clickCount', 'totalConversion', 'campaigns'));
  }

  public function exportReportOrder(Request $request) 
  {
    return Excel::download(new ReportOrderExport($request), 'tk-report-'.Carbon::now()->format('YmdHis').'-'.time().'.xlsx');
  }
}
