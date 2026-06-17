<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return view('orders.index', [
            'orders' => $request->user()->orders()->with('plan')->latest()->get(),
        ]);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);

        if ($plan->isFree()) {
            return redirect()->route('dashboard')->with('warning', 'Paket Free tidak perlu pembayaran.');
        }

        $order = $request->user()->orders()->create([
            'plan_id' => $plan->id,
            'order_code' => Order::generateCode(),
            'amount' => $plan->price,
            'payment_method' => 'manual_transfer',
            'payment_status' => 'unpaid',
        ]);

        return redirect()->route('orders.show', $order);
    }

    public function show(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        return view('orders.show', [
            'order' => $order->load('plan'),
            'bankInfo' => config('app.payment_manual_bank', env('PAYMENT_MANUAL_BANK', 'Transfer manual — hubungi admin')),
        ]);
    }

    public function uploadProof(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $request->validate([
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'proof.required' => 'Upload bukti transfer dulu ya.',
        ]);

        if ($order->proof_path) {
            Storage::disk('local')->delete($order->proof_path);
        }

        $path = $request->file('proof')->store("proofs/{$order->user_id}", 'local');
        $order->update(['proof_path' => $path]);

        return back()->with('success', 'Bukti transfer terkirim. Admin akan memverifikasi pembayaran kamu.');
    }
}
