<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiLog;
use App\Models\CvReview;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'active_subscriptions' => Subscription::where('status', 'active')->where('ends_at', '>=', now())->count(),
            'total_orders' => Order::count(),
            'revenue' => Order::where('payment_status', 'paid')->sum('amount'),
            'cv_reviews_today' => CvReview::whereDate('created_at', today())->count(),
            'ai_usage_today' => AiLog::whereDate('created_at', today())->count(),
            'pending_payments' => Order::where('payment_status', 'unpaid')->count(),
        ];

        return view('admin.dashboard', ['stats' => $stats]);
    }
}
