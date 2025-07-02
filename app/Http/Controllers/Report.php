<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Click;
use App\Models\Campaign;
use Carbon\Carbon;
use App\Exports\ReportOrderExport;
use App\Exports\ReportPerformanceExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Report\ReportService;

class Report extends Controller
{
  const PER_PAGE = 50;
  const DEFAULT_SUB_DAYS = 7;

  public function performance(Request $request, ReportService $reportService)
  {
    $group = $request->group;
    if (empty($group)) {
      $group = 'order_time';
    }
    $groupSelect = '';
    if ($group == 'campaign_id') {
      $groupSelect = 'campaign_id';
    } else if ($group == 'user_id') {
      $groupSelect = 'user_id';
    } else if ($group == 'order_time') {
      $groupSelect = "date_format(order_time, '%Y-%m-%d') date";
    }

    $data = $reportService->getPerformanceQueryBuilderObject($request, $group, $groupSelect);

    $totalConversion = $data->get()->sum(function ($item) {
      return $item->cnt;
    });

    $totalPrice = $data->get()->sum(function ($item) {
      return $item->total_price;
    });

    $totalCom = $data->get()->sum(function ($item) {
      return $item->total_com;
    });

    $totalComSys = $data->get()->sum(function ($item) {
      return $item->total_com_sys;
    });

    $data = $data->paginate(self::PER_PAGE)->withQueryString();

    if ($group == 'order_time') {
      $groupSelect = "date_format(clicks.created_at, '%Y-%m-%d') date";
    }

    $clicks = $reportService->getPerformanceClickQueryBuilderObject($request, $group, $groupSelect);

    $clickCount = $clicks->get()->sum(function ($item) {
      return $item->cnt;
    });

    $clicks = $clicks->get();

    $clicks = $clicks->keyBy($group == 'order_time' ? 'date' : $group)->toArray();

    $campaigns = Campaign::where('status', 1)->get();

    return view('content.reports.performance', compact('data', 'clicks', 'totalConversion', 'clickCount', 'totalPrice', 'totalCom', 'totalComSys', 'campaigns'));
  }

  public function order(Request $request, ReportService $reportService) {
    $data = $reportService->getOrdersQueryBuilderObject($request);

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
    return Excel::download(new ReportOrderExport($request, app(ReportService::class)), 'tk-report-order-'.Carbon::now()->format('YmdHis').'-'.time().'.xlsx');
  }

  public function exportReportPerformance(Request $request) 
  {
    return Excel::download(new ReportPerformanceExport($request, app(ReportService::class)), 'tk-report-performance-'.Carbon::now()->format('YmdHis').'-'.time().'.xlsx');
  }
}
