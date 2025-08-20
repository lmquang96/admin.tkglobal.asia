<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversion;
use App\Models\Transaction;
use App\Models\Balance;
use App\Models\AdvancePaymentHistory;
use Carbon\Carbon;

class ScanTransaction extends Controller
{
    const PAID_MONTH_CAMPAIGN = [
        1, // Klook - CPS
        2, // Trip.com - CPS
        16 // KKday Global - CPS
    ];

    public function index(Request $request) {
        $month = $request->month;
        if (!$month) {
            $month = Carbon::now()->format('Y-m');
        }

        $transaction = Transaction::where('target_month', $month)
        ->when($request->by_business, function($q, $by_business) {
            $geo = $by_business == 'TKFUNNEL' ? 'hk' : 'vn';
            return $q->join('campaigns', 'campaigns.id', '=', 'transactions.campaign_id')
          ->where('geo', $geo);
        });

        $totalAmountPub = $transaction->get()->sum(function ($item) {
            return $item->amount_pub;
        });

        $totalAmountSys = $transaction->get()->sum(function ($item) {
            return $item->amount_sys;
        });

        $transaction = $transaction->get();

        return view('content.scanTransaction.index', compact('transaction', 'totalAmountPub', 'totalAmountSys'));
    }

    public function scan(Request $request) {
        $month = $request->month;
        $campaignId = $request->campaignId;

        // Transaction::where('target_month', $month)->where('campaign_id', $campaignId)->delete();

        if (in_array($campaignId, self::PAID_MONTH_CAMPAIGN)) {
            $orderTimeStart = Carbon::parse($month.'-01 00:00:00')->subMonths(6)->format('Y-m-d h:i:s');
            $conversions = Conversion::query()
            ->whereBetween('paid_at', [$month.'-01 00:00:00', $month.'-31 23:59:59'])
            ->whereBetween('order_time', [$orderTimeStart, $month.'-31 23:59:59'])
            ->where('status', 'Approved')
            ->where('campaign_id', $campaignId)
            ->whereNull('comment')
            ->selectRaw('sum(commission_pub) commission_pub, sum(commission_sys) commission_sys, campaign_id, user_id');
        } else {
            $conversions = Conversion::query()
            ->whereBetween('order_time', [$month.'-01 00:00:00', $month.'-31 23:59:59'])
            ->where('status', 'Approved')
            ->where('campaign_id', $campaignId)
            ->selectRaw('sum(commission_pub) commission_pub, sum(commission_sys) commission_sys, campaign_id, user_id');
        }

        $conversionsByCampaign = $conversions->groupBy('user_id', 'campaign_id')
        ->get();

        $conversionsByUser = $conversions->groupBy('user_id')
        ->get();

        // UPDATE CONVERSIONS STATUS TO PAID

        foreach($conversionsByCampaign as $key => $conversion) {
            Transaction::create([
                'code' => sha1(time() + $key),
                'target_month' => $month,
                'amount_pub' => $conversion->commission_pub,
                'amount_sys' => $conversion->commission_sys,
                'campaign_id' => $conversion->campaign_id,
                'user_id' => $conversion->user_id
            ]);
        }

        foreach($conversionsByUser as $key => $conversion) {

            $balanceAmount = $conversion->commission_pub;

            $balance = Balance::where('user_id', $conversion->user_id)->pluck('balance')->first();
            if (!$balance) {
                Balance::create([
                    'code' => sha1(time() + $key),
                    'balance' => $balanceAmount,
                    'last_updated' => Carbon::now(),
                    'user_id' => $conversion->user_id
                ]);
            } else {
                $balanceAmount = $balance + $balanceAmount;
                Balance::where('user_id', $conversion->user_id)->update([
                    'balance' => $balanceAmount,
                    'last_updated' => Carbon::now()
                ]);
            }
        }

        return response()->json($conversions, 200);
    }
}
