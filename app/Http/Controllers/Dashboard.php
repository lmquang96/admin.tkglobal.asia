<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversion;
use App\Models\Click;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Cache;
use App\Services\User\UserService;

class Dashboard extends Controller
{
  const DEFAULT_SUB_DAYS = 6;

  function index(Request $request, UserService $userService) {
    $subDays = self::DEFAULT_SUB_DAYS;
    if ($request->date) {
      $date = explode(' - ', $request->date);
      $sDate = $date[0];
      $eDate = $date[1];
      $subDays = Carbon::parse($sDate)->diffInDays(Carbon::parse($eDate));
    } else {
      $eDate = Carbon::now()->format('Y-m-d');
      $sDate = Carbon::now()->subDays($subDays)->format('Y-m-d');
    }
    $byBusiness = $request->by_business;
    $eDateChange = Carbon::now()->subDays($subDays+1)->format('Y-m-d');
    $sDateChange = Carbon::now()->subDays(2*$subDays+1)->format('Y-m-d');

    $statistics = Cache::remember('statistics'.$sDate.$eDate.$byBusiness, 600, function() use ($sDate, $eDate, $byBusiness) {
      return self::getStatistics($sDate, $eDate, $byBusiness);
    });
    $statisticsChange = Cache::remember('statistics_change'.$sDate.$eDate.$byBusiness, 600, function() use ($sDateChange, $eDateChange, $byBusiness) {
      return self::getStatistics($sDateChange, $eDateChange, $byBusiness);
    });

    $totalCom = $statistics['totalCom'];
    $totalSales = $statistics['totalSales'];
    $clickCount = $statistics['clickCount'];
    $totalConversion = $statistics['totalConversion'];
    $totalComSys = $statistics['totalComSys'];

    $totalComChange = $statisticsChange['totalCom'] > 0 ? ($statistics['totalCom'] / $statisticsChange['totalCom'] * 100) - 100 : 100;
    $totalComSysChange = $statisticsChange['totalComSys'] > 0 ? ($statistics['totalComSys'] / $statisticsChange['totalComSys'] * 100) - 100 : 100;
    $totalSalesChange = $statisticsChange['totalSales'] > 0 ? ($statistics['totalSales'] / $statisticsChange['totalSales'] * 100) - 100 : 100;
    $clickCountChange = $statisticsChange['clickCount'] > 0 ? ($statistics['clickCount'] / $statisticsChange['clickCount'] * 100) - 100 : 100;
    $totalConversionChange = $statisticsChange['totalConversion'] > 0 ? ($statistics['totalConversion'] / $statisticsChange['totalConversion'] * 100) - 100 : 100;

    $topAffiliates = Cache::remember('top_afffiliates'.$sDate.$eDate.$byBusiness, 600, function() use ($sDate, $eDate, $byBusiness, $userService) {
      return $userService->getTopAffiliates($sDate, $eDate, $byBusiness);
    });

    return view('content.dashboard.index', compact('totalCom', 'totalSales', 'clickCount', 'totalConversion',
      'totalComChange', 'totalSalesChange', 'clickCountChange', 'totalConversionChange', 'topAffiliates', 'totalComSys', 'totalComSysChange', 'subDays'));
  }

  function getStatistics($sDate, $eDate, $byBusiness) {
    $query = Conversion::query()
    ->whereBetween('order_time', [$sDate.' 00:00:00', $eDate.' 23:59:59'])
    ->when($byBusiness && !empty($byBusiness), function($q) use($byBusiness) {
      $geo = $byBusiness == 'TKFUNNEL' ? 'hk' : 'vn';
        return $q->join('campaigns', 'campaigns.id', '=', 'conversions.campaign_id')
      ->where('geo', $geo);
    });

    $totalCom = $query->sum('commission_pub');
    $totalComSys = $query->sum('commission_sys');
    $totalSales = $query->selectRaw('sum(unit_price * quantity) as sales')->pluck('sales')->first();

    $totalConversion = $query->count();

    $clickCount = Click::query()
    ->join('link_histories', 'link_histories.id', '=', 'clicks.link_history_id')
    ->whereBetween('clicks.created_at', [$sDate.' 00:00:00', $eDate.' 23:59:59'])
    ->when($byBusiness && !empty($byBusiness), function($q) use($byBusiness) {
      $geo = $byBusiness == 'TKFUNNEL' ? 'hk' : 'vn';
      return $q->join('campaigns', 'campaigns.id', '=', 'link_histories.campaign_id')
        ->where('campaigns.geo', $geo);
    })
    ->selectRaw('count(*) cnt')
    ->pluck('cnt')->first();

    return [
      'totalCom' => $totalCom,
      'totalComSys' => $totalComSys,
      'totalSales' => $totalSales ?? 0,
      'clickCount' => $clickCount,
      'totalConversion' => $totalConversion
    ];
  }

  function getDataChart(Request $request) {
    $subDays = self::DEFAULT_SUB_DAYS;
    if ($request->date) {
      $date = explode(' - ', $request->date);
      $sDate = $date[0];
      $eDate = $date[1];
      $subDays = Carbon::parse($sDate)->diffInDays(Carbon::parse($eDate));
    } else {
      $eDate = Carbon::now()->format('Y-m-d');
      $sDate = Carbon::now()->subDays($subDays)->format('Y-m-d');
    }
    $byBusiness = $request->by_business;

    $result = Cache::remember('chart_data'.$sDate.$eDate.$byBusiness, 600, function() use ($sDate, $eDate, $byBusiness) {
      $merged = [];
      $comData = self::getComData($sDate, $eDate, $byBusiness);
      $clickData = self::getClickData($sDate, $eDate, $byBusiness);
      
      $merged['com'] = $comData;
      $merged['click'] = $clickData;

      return $merged;
    });

    return response()->json([
      'status' => 200,
      'data' => $result
    ]);
  }

  function getComData($sDate, $eDate, $byBusiness) {
    $query = Conversion::query()
    ->whereBetween('order_time', [$sDate.' 00:00:00', $eDate.' 23:59:59'])
    ->when($byBusiness && !empty($byBusiness), function($q) use($byBusiness) {
      $geo = $byBusiness == 'TKFUNNEL' ? 'hk' : 'vn';
      return $q->join('campaigns', 'campaigns.id', '=', 'conversions.campaign_id')
        ->where('campaigns.geo', $geo);
    })
    ->selectRaw("DATE_FORMAT(order_time, '%Y-%m-%d') as time, sum(commission_pub) as sumcom")
    ->groupBy('time')
    ->pluck('sumcom', 'time');

    $dates = collect();
    // for ($i = 6; $i >= 0; $i--) {
    //   $dates->put(Carbon::today()->subDays($i)->toDateString(), 0);
    // }

    $start = Carbon::parse($sDate);
    $end = Carbon::parse($eDate);

    $period = CarbonPeriod::create($start, $end);

    foreach ($period as $date) {
      $dates->put($date->toDateString(), 0);
    }

    $result = $dates->merge($query)->toArray();

    $result = array_map('floatval', $result);

    return $result;
  }

  function getClickData($sDate, $eDate, $byBusiness) {
    $query = Click::query()
    ->join('link_histories', 'link_histories.id', '=', 'clicks.link_history_id')
    ->whereBetween('clicks.created_at', [$sDate.' 00:00:00', $eDate.' 23:59:59'])
    ->when($byBusiness && !empty($byBusiness), function($q) use($byBusiness) {
      $geo = $byBusiness == 'TKFUNNEL' ? 'hk' : 'vn';
      return $q->join('campaigns', 'campaigns.id', '=', 'link_histories.campaign_id')
        ->where('campaigns.geo', $geo);
    })
    ->selectRaw("DATE_FORMAT(clicks.created_at, '%Y-%m-%d') as time, count(clicks.id) as cnt")
    ->groupBy('time')
    ->pluck('cnt', 'time');

    $dates = collect();
    for ($i = Carbon::parse($sDate); $i->lte(Carbon::parse($eDate)); $i->addDay()) {
      $dates->put($i->toDateString(), 0);
    }

    $result = $dates->merge($query)->toArray();

    $result = array_map('intval', $result);

    return $result;
  }
}
