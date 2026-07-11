<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = ActivityLog::query()
            ->with('user')
            ->when(filled($request->query('action')), fn ($query) => $query->where('action', 'like', $request->query('action').'%'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.logs.index', ['logs' => $logs]);
    }
}
