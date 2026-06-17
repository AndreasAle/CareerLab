<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if ($search = $request->input('q')) {
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
        }
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        return view('admin.users.index', [
            'users' => $query->latest()->paginate(20)->withQueryString(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', 'in:admin,coach,user'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user->update([
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'User diperbarui.');
    }

    public function toggleActive(Request $request, User $user)
    {
        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', 'Status user diubah.');
    }
}
