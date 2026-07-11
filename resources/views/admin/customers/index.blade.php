<x-admin-layout title="ลูกค้า">
    <form method="GET" class="flex flex-wrap items-center gap-2 rounded-2xl border border-gray-100 bg-white p-3 shadow-sm">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="ค้นหาชื่อ อีเมล หรือเบอร์โทร..."
               class="w-72 rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
        <button type="submit" class="rounded-xl bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700">ค้นหา</button>
        @if (request('q'))
            <a href="{{ route('admin.customers.index') }}" class="text-sm text-gray-400 hover:text-gray-600">ล้าง</a>
        @endif
    </form>

    <div class="mt-4 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-400">
                    <tr>
                        <th class="px-5 py-3 font-medium">ลูกค้า</th>
                        <th class="px-5 py-3 font-medium">เบอร์โทร</th>
                        <th class="px-5 py-3 font-medium">คำสั่งซื้อ</th>
                        <th class="px-5 py-3 font-medium">ยอดซื้อสะสม</th>
                        <th class="px-5 py-3 font-medium">สถานะ</th>
                        <th class="px-5 py-3 font-medium">สมัครเมื่อ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($customers as $customer)
                        <tr class="hover:bg-gray-50/60">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-primary-100 text-sm font-bold text-primary-600">{{ mb_substr($customer->name, 0, 1) }}</span>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $customer->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $customer->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-gray-500">{{ $customer->phone ?? '-' }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ number_format($customer->orders_count) }}</td>
                            <td class="px-5 py-3 font-semibold">{{ money((float) ($customer->total_spent ?? 0)) }}</td>
                            <td class="px-5 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $customer->isBanned() ? 'bg-red-50 text-red-500' : 'bg-emerald-50 text-emerald-600' }}">
                                    {{ $customer->status->label() }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-gray-400">{{ $customer->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">ไม่พบลูกค้า</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-50 px-5 py-4">{{ $customers->links() }}</div>
    </div>
</x-admin-layout>
