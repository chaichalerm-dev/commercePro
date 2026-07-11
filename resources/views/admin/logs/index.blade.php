<x-admin-layout title="Activity Logs">
    <form method="GET" class="flex flex-wrap items-center gap-2 rounded-2xl border border-gray-100 bg-white p-3 shadow-sm">
        <input type="search" name="action" value="{{ request('action') }}" placeholder="กรองตาม action เช่น product"
               class="w-64 rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
        <button type="submit" class="rounded-xl bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700">กรอง</button>
        @if (request('action'))
            <a href="{{ route('admin.logs.index') }}" class="text-sm text-gray-400 hover:text-gray-600">ล้าง</a>
        @endif
    </form>

    <div class="mt-4 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-400">
                    <tr>
                        <th class="px-5 py-3 font-medium">เวลา</th>
                        <th class="px-5 py-3 font-medium">ผู้ใช้</th>
                        <th class="px-5 py-3 font-medium">Action</th>
                        <th class="px-5 py-3 font-medium">รายละเอียด</th>
                        <th class="px-5 py-3 font-medium">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-gray-50/60">
                            <td class="whitespace-nowrap px-5 py-3 text-gray-400">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td class="px-5 py-3">{{ $log->user?->name ?? 'ระบบ' }}</td>
                            <td class="px-5 py-3"><code class="rounded bg-gray-100 px-2 py-0.5 text-xs text-gray-700">{{ $log->action }}</code></td>
                            <td class="max-w-md truncate px-5 py-3 text-gray-500">{{ $log->properties ? json_encode($log->properties, JSON_UNESCAPED_UNICODE) : '-' }}</td>
                            <td class="px-5 py-3 text-gray-400">{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400">ยังไม่มีบันทึกกิจกรรม</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-50 px-5 py-4">
            {{ $logs->links() }}
        </div>
    </div>
</x-admin-layout>
