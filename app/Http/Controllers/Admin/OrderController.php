<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'plan'])->latest();
        if ($status = $request->input('status')) {
            $query->where('payment_status', $status);
        }

        return view('admin.orders.index', ['orders' => $query->paginate(20)->withQueryString()]);
    }

    /**
     * Approve a manual payment: mark order paid + activate subscription.
     */
    public function approve(Order $order)
    {
        if ($order->payment_status === 'paid') {
            return back()->with('warning', 'Order ini sudah dibayar.');
        }
        if (! $order->plan) {
            return back()->with('error', 'Order tidak punya plan.');
        }

        DB::transaction(function () use ($order) {
            $order->update(['payment_status' => 'paid', 'paid_at' => now()]);

            // Expire any current active subscription, then create the new one.
            Subscription::where('user_id', $order->user_id)->where('status', 'active')->update(['status' => 'expired']);

            Subscription::create([
                'user_id' => $order->user_id,
                'plan_id' => $order->plan_id,
                'starts_at' => now(),
                'ends_at' => now()->addDays($order->plan->duration_days),
                'status' => 'active',
            ]);
        });

        return back()->with('success', "Pembayaran {$order->order_code} disetujui & langganan diaktifkan.");
    }

    public function reject(Order $order)
    {
        $order->update(['payment_status' => 'failed']);

        return back()->with('success', "Order {$order->order_code} ditolak.");
    }
}
