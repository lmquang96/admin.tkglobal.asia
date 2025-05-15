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
    public function index(Request $request) {
        $month = $request->month;
        if (!$month) {
            $month = Carbon::now()->format('Y-m');
        }

        $transaction = Transaction::where('target_month', $month);

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

        Transaction::where('target_month', $month)->delete();

        $conversions = Conversion::query()
        ->whereBetween('order_time', [$month.'-01 00:00:00', $month.'-31 23:59:59'])
        ->where('status', 'Approved')
        ->selectRaw('sum(commission_pub) commission_pub, sum(commission_sys) commission_sys, campaign_id, user_id');

        $conversionsByCampaign = $conversions->groupBy('user_id', 'campaign_id')
        ->get();

        $conversionsByUser = $conversions->groupBy('user_id')
        ->get();

        $advancePayment = AdvancePaymentHistory::where('target_month', $month)
        ->where('status', 0)
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
