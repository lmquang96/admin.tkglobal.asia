<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentRequest as PaymentRequestModel;
use App\Models\Balance;
use App\Models\AdvancePaymentHistory;
use App\Models\Profile;
use Carbon\Carbon;

class PaymentRequest extends Controller
{
  public function index(Request $request) {
    if (!$request->month) {
      $request->merge(['month' => Carbon::now()->format('Y-m')]);
    }
    
    $paymentRequests = PaymentRequestModel::where('submission_date', 'like', $request->month . '%')
    ->join('profiles', 'profiles.user_id', '=', 'payment_requests.user_id')
    ->when($request->status, function($q, $status) {
      $q->where('status', $status);
    })
    ->when($request->account_type, function($q, $account_type) {
      $q->where('profiles.account_type', $account_type);
    });

    $totalRequests = $paymentRequests->count();

    $totalAmount = $paymentRequests->sum('amount');

    $totalTax = $paymentRequests->get()->sum(function ($item) {
      return $item->amount > 2000000 ? $item->amount * 0.1 : 0;
    });

    $paymentRequests = $paymentRequests->get();

    return view('content.payment.request', compact('paymentRequests', 'totalRequests', 'totalAmount', 'totalTax'));
  }

  public function changeStatus(Request $request) {
    $status = $request->status;
    $code = $request->code;

    $paymentRequest = PaymentRequestModel::where('code', $code)->first();

    if ($status == '2') {
      $balance = Balance::where('user_id', $paymentRequest->user_id)->first();

      if (!$balance) {
        Balance::create([
          'user_id' => $paymentRequest->user_id,
          'balance' => 0
        ]);
      }
    }

    PaymentRequestModel::where('code', $code)->update(['status' => $status]);
    return redirect()->back();
  }

  public function advancePaymentHistory(Request $request) {
    if (!$request->month) {
      $request->merge(['month' => Carbon::now()->format('Y-m')]);
    }

    $data = AdvancePaymentHistory::query()
    ->where('target_month', '=', $request->month);

    $totalAmount = $data->sum('amount');

    $data = $data->get();

    return view('content.payment.advanceHistory', compact('data', 'totalAmount'));
  }

  public function advancePayment(Request $request) {
    $affiliate_id = $request->affiliate_id;

    $profile = Profile::query()
    ->where('affiliate_id', $affiliate_id)
    ->first();
    $userId = $profile->user_id;

    AdvancePaymentHistory::create([
      'code' => sha1(time()),
      'target_month' => $request->month,
      'amount' => $request->amount,
      'user_id' => $userId
    ]);

    return response()->json([], 200, []);
  }
  
  public function deleteAdvancePayment($id) {
    AdvancePaymentHistory::where('id', $id)->delete();
    return redirect()->back()->with('message', 'Xóa thành công!');
  }
}
