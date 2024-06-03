<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::where('user_id', auth()->id())->get();
        return view('payments.index', compact('payments'));
    }

    public function pay(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->payment_status = $request->input('payment_status');
        $payment->save();

        return response()->json(['success' => true]);
    }
}
