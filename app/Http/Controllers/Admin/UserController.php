<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->with('role')
            ->adminTier()
            ->when(filled($request->query('q')), function ($query) use ($request): void {
                $term = trim((string) $request->query('q'));
                $operator = DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
                $query->whereAny(['name', 'email'], $operator, "%{$term}%");
            })
            ->when(filled($request->query('role')), fn ($query) => $query->where('role_id', $request->integer('role')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => UserRole::adminTiers(),
        ]);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', __('admin/users.flash.cannot_change_own_role'));
        }

        $validated = $request->validate([
            'role_id' => ['required', Rule::in(array_map(fn (UserRole $role) => $role->value, UserRole::adminTiers()))],
        ]);

        $user->update(['role_id' => UserRole::from((int) $validated['role_id'])]);

        ActivityLog::record('user.role_changed', $user, ['name' => $user->name, 'role' => $user->role_id->name]);

        return back()->with('success', __('admin/users.flash.role_changed', [
            'name' => $user->name,
            'role' => $user->role_id->label(),
        ]));
    }

    public function toggleBan(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', __('admin/users.flash.cannot_ban_self'));
        }

        $user->update(['status' => $user->isBanned() ? UserStatus::Active : UserStatus::Banned]);

        ActivityLog::record('user.status_changed', $user, ['name' => $user->name, 'status' => $user->status->value]);

        return back()->with('success', $user->isBanned()
            ? __('admin/users.flash.banned', ['name' => $user->name])
            : __('admin/users.flash.unbanned', ['name' => $user->name]));
    }
}
