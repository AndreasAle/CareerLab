<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiLog;
use Illuminate\Http\Request;

class AiLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AiLog::with('user')->latest();
        if ($feature = $request->input('feature')) {
            $query->where('feature_key', $feature);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        return view('admin.ai-logs.index', [
            'logs' => $query->paginate(30)->withQueryString(),
            'features' => AiLog::distinct()->pluck('feature_key'),
        ]);
    }
}
