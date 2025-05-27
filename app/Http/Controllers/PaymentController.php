<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{

    public function index()
    {
        $payments = Payment::latest()->get();
        return view('payments.index', compact('payments'));
    }

    /**
     * Store the payment form data.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:payments,email',
            'payment_method' => 'required|in:JazzCash,EasyPaisa,Bank,GooglePay,Paypal,CreditCard/DebitCard',
            'plan' => 'required|in:Monthly,Yearly',
            'attachment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('attachment')) {
            $filename = $request->file('attachment')->store('payment_receipts', 'public');
        }

        $payment = new Payment();
        $payment->name = $request->name;
        $payment->email = $request->email;
        $payment->payment_method = $request->payment_method;
        $payment->plan = $request->plan;
        $payment->attachment = $filename ?? null;
        $payment->save();

        return redirect()->back()->with('success', 'Payment submitted successfully!');
    }

    public function destroy(Payment $payment)
    {
        if ($payment->attachment) {
            Storage::disk('public')->delete($payment->attachment);
        }
        $payment->delete();

        return redirect()->back()->with('success', 'Payment deleted successfully!');
    }
}
