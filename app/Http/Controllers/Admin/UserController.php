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
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->with('role')
            ->when(filled($request->query('q')), function ($query) use ($request): void {
                $term = trim((string) $request->query('q'));
                $operator = $query->getConnection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
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
            return back()->with('error', 'ไม่สามารถเปลี่ยนบทบาทของตัวเองได้');
        }

        $validated = $request->validate(['role_id' => ['required', Rule::enum(UserRole::class)]]);

        $user->update(['role_id' => UserRole::from((int) $validated['role_id'])]);

        ActivityLog::record('user.role_changed', $user, ['role' => $user->role_id->name]);

        return back()->with('success', "เปลี่ยนบทบาทของ {$user->name} เป็น {$user->role_id->label()} แล้ว");
    }

    public function toggleBan(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'ไม่สามารถระงับบัญชีของตัวเองได้');
        }

        $user->update(['status' => $user->isBanned() ? UserStatus::Active : UserStatus::Banned]);

        ActivityLog::record('user.status_changed', $user, ['status' => $user->status->value]);

        return back()->with('success', $user->isBanned()
            ? "ระงับบัญชี {$user->name} แล้ว"
            : "ปลดระงับบัญชี {$user->name} แล้ว");
    }
}
