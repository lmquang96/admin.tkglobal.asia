<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversion;
use App\Models\Transaction;
use App\Models\Balance;
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
        ->selectRaw('sum(commission_pub) commission_pub, sum(commission_sys) commission_sys, campaign_id, user_id')
        ->groupBy('user_id')
        ->get();

        // UPDATE CONVERSIONS STATUS TO PAID

        foreach($conversions as $key => $conversion) {
            Transaction::create([
                'code' => sha1(time() + $key),
                'target_month' => $month,
                'amount_pub' => $conversion->commission_pub,
                'amount_sys' => $conversion->commission_sys,
                'campaign_id' => $conversion->campaign_id,
                'user_id' => $conversion->user_id
            ]);

            $balance = Balance::where('user_id', $conversion->user_id)->pluck('balance')->first();
            if (!$balance) {
                Balance::create([
                    'code' => sha1(time() + $key),
                    'balance' => $conversion->commission_pub,
                    'last_updated' => Carbon::now(),
                    'user_id' => $conversion->user_id
                ]);
            } else {
                Balance::where('user_id', $conversion->user_id)->update([
                    'balance' => $balance + $conversion->commission_pub,
                    'last_updated' => Carbon::now()
                ]);
            }
        }
        
        return response()->json($conversions, 200);
    }
}
