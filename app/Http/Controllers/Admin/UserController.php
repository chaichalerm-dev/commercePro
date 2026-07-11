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
            'roles' => UserRole::cases(),
        ]);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'à¹„à¸¡à¹ˆà¸ªà¸²à¸¡à¸²à¸£à¸–à¹€à¸›à¸¥à¸µà¹ˆà¸¢à¸™à¸šà¸—à¸šà¸²à¸—à¸‚à¸­à¸‡à¸•à¸±à¸§à¹€à¸­à¸‡à¹„à¸”à¹‰');
        }

        $validated = $request->validate(['role_id' => ['required', Rule::enum(UserRole::class)]]);

        $user->update(['role_id' => UserRole::from((int) $validated['role_id'])]);

        ActivityLog::record('user.role_changed', $user, ['role' => $user->role_id->name]);

        return back()->with('success', "à¹€à¸›à¸¥à¸µà¹ˆà¸¢à¸™à¸šà¸—à¸šà¸²à¸—à¸‚à¸­à¸‡ {$user->name} à¹€à¸›à¹‡à¸™ {$user->role_id->label()} à¹à¸¥à¹‰à¸§");
    }

    public function toggleBan(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'à¹„à¸¡à¹ˆà¸ªà¸²à¸¡à¸²à¸£à¸–à¸£à¸°à¸‡à¸±à¸šà¸šà¸±à¸à¸Šà¸µà¸‚à¸­à¸‡à¸•à¸±à¸§à¹€à¸­à¸‡à¹„à¸”à¹‰');
        }

        $user->update(['status' => $user->isBanned() ? UserStatus::Active : UserStatus::Banned]);

        ActivityLog::record('user.status_changed', $user, ['status' => $user->status->value]);

        return back()->with('success', $user->isBanned()
            ? "à¸£à¸°à¸‡à¸±à¸šà¸šà¸±à¸à¸Šà¸µ {$user->name} à¹à¸¥à¹‰à¸§"
            : "à¸›à¸¥à¸”à¸£à¸°à¸‡à¸±à¸šà¸šà¸±à¸à¸Šà¸µ {$user->name} à¹à¸¥à¹‰à¸§");
    }
}
