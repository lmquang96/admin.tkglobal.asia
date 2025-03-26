<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversion;
use App\Models\Click;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class Dashboard extends Controller
{
  const DEFAULT_SUB_DAYS = 6;

  function index(Request $request) {
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
    $eDateChange = Carbon::now()->subDays($subDays+1)->format('Y-m-d');
    $sDateChange = Carbon::now()->subDays(2*$subDays+1)->format('Y-m-d');

    $statistics = Cache::remember('statistics'.$sDate.$eDate, 600, function() use ($sDate, $eDate) {
      return self::getStatistics($sDate, $eDate);
    });
    $statisticsChange = Cache::remember('statistics_change'.$sDate.$eDate, 600, function() use ($sDateChange, $eDateChange) {
      return self::getStatistics($sDateChange, $eDateChange);
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

    $topAffiliates = Cache::remember('top_afffiliates'.$sDate.$eDate, 600, function() use ($sDate, $eDate) {
      return self::getTopAffiliates($sDate, $eDate);
    });

    return view('content.dashboard.index', compact('totalCom', 'totalSales', 'clickCount', 'totalConversion',
      'totalComChange', 'totalSalesChange', 'clickCountChange', 'totalConversionChange', 'topAffiliates', 'totalComSys', 'totalComSysChange', 'subDays'));
  }

  function getStatistics($sDate, $eDate) {
    $query = Conversion::query()
    ->whereBetween('order_time', [$sDate.' 00:00:00', $eDate.' 23:59:59']);

    $totalCom = $query->sum('commission_pub');
    $totalComSys = $query->sum('commission_sys');
    $totalSales = $query->selectRaw('sum(unit_price * quantity) as sales')->pluck('sales')->first();

    $totalConversion = $query->count();

    $clickCount = Click::query()
    ->join('link_histories', 'link_histories.id', '=', 'clicks.link_history_id')
    ->whereBetween('clicks.created_at', [$sDate.' 00:00:00', $eDate.' 23:59:59'])
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

    $result = Cache::remember('chart_data'.$sDate.$eDate, 600, function() use ($sDate, $eDate) {
      $merged = [];
      $comData = self::getComData($sDate, $eDate);
      $clickData = self::getClickData($sDate, $eDate);
      
      $merged['com'] = $comData;
      $merged['click'] = $clickData;

      return $merged;
    });

    return response()->json([
      'status' => 200,
      'data' => $result
    ]);
  }

  function getComData($sDate, $eDate) {
    $query = Conversion::query()
    ->whereBetween('order_time', [$sDate.' 00:00:00', $eDate.' 23:59:59'])
    ->selectRaw("DATE_FORMAT(order_time, '%Y-%m-%d') as time, sum(commission_pub) as sumcom")
    ->groupBy('time')
    ->pluck('sumcom', 'time');

    $dates = collect();
    for ($i = 6; $i >= 0; $i--) {
      $dates->put(Carbon::today()->subDays($i)->toDateString(), 0);
    }

    $result = $dates->merge($query)->toArray();

    $result = array_map('floatval', $result);

    return $result;
  }

  function getClickData($sDate, $eDate) {
    $query = Click::query()
    ->join('link_histories', 'link_histories.id', '=', 'clicks.link_history_id')
    ->whereBetween('clicks.created_at', [$sDate.' 00:00:00', $eDate.' 23:59:59'])
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

  function getTopAffiliates($sDate, $eDate) {
    return Conversion::query()
    ->join('users', 'users.id' , '=', 'conversions.user_id')
    ->join('profiles', 'users.id', '=', 'profiles.user_id')
    ->selectRaw('email, affiliate_id, sum(commission_pub) as sumcom')
    ->whereBetween('order_time', [$sDate.' 00:00:00', $eDate.' 23:59:59'])
    ->groupBy('email', 'affiliate_id')
    ->orderByDesc('sumcom')
    ->limit(6)
    ->get();
  }
}
