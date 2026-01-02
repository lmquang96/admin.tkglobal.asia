<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommissionRate as CommissionRateModel;
use App\Models\User;

class CommissionRate extends Controller
{
    public function index() {
        $rates = CommissionRateModel::get();
        $users = User::select('id', 'name')->get();

        return view('content.commissionRate.index', compact('rates', 'users'));
    }

    public function create(Request $request) {
        $accountId = $request->account_id;
        $rate = $request->rate;

        CommissionRateModel::updateOrCreate(
            [
                'user_id' => $accountId
            ],[
                'rate' => $rate / 100
            ]
        );

        return response()->json([], 200, []);
    }
}
