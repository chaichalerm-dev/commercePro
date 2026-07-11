<x-admin-layout title="Dashboard">
    {{-- Stat cards --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <x-admin.stat-card label="รายได้ทั้งหมด" :value="money($cards['revenue'])" color="primary"
            icon="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        <x-admin.stat-card label="คำสั่งซื้อทั้งหมด" :value="number_format($cards['orders'])" color="blue"
            icon="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
        <x-admin.stat-card label="รอดำเนินการ" :value="number_format($cards['pending_orders'])" color="amber"
            icon="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        <x-admin.stat-card label="สินค้า" :value="number_format($cards['products'])" color="violet"
            icon="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
        <x-admin.stat-card label="ลูกค้า" :value="number_format($cards['customers'])" color="emerald"
            icon="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
    </div>

    {{-- Charts --}}
    <div class="mt-6 grid gap-4 lg:grid-cols-3">
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm lg:col-span-2">
            <h2 class="font-semibold text-gray-900">รายได้ 30 วันล่าสุด</h2>
            <div class="mt-4 h-72">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <h2 class="font-semibold text-gray-900">คำสั่งซื้อตามสถานะ</h2>
            <div class="mt-4 flex h-72 items-center justify-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <script type="application/json" id="dashboard-data">
        {!! json_encode(['revenue' => $revenueChart, 'status' => $ordersByStatus], JSON_UNESCAPED_UNICODE) !!}
    </script>

    {{-- Recent orders --}}
    <div class="mt-6 grid gap-4 lg:grid-cols-3">
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between border-b border-gray-100 p-5">
                <h2 class="font-semibold text-gray-900">คำสั่งซื้อล่าสุด</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-400">
                        <tr>
                            <th class="px-5 py-3 font-medium">เลขที่</th>
                            <th class="px-5 py-3 font-medium">ลูกค้า</th>
                            <th class="px-5 py-3 font-medium">ยอดรวม</th>
                            <th class="px-5 py-3 font-medium">สถานะ</th>
                            <th class="px-5 py-3 font-medium">ชำระเงิน</th>
                            <th class="px-5 py-3 font-medium">วันที่</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($recentOrders as $order)
                            <tr class="hover:bg-gray-50/60">
                                <td class="px-5 py-3 font-medium text-primary-600">{{ $order->order_number }}</td>
                                <td class="px-5 py-3">{{ $order->user->name }}</td>
                                <td class="px-5 py-3 font-semibold">{{ money((float) $order->grand_total) }}</td>
                                <td class="px-5 py-3"><span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $order->status->color() }}">{{ $order->status->label() }}</span></td>
                                <td class="px-5 py-3"><span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $order->payment_status->color() }}">{{ $order->payment_status->label() }}</span></td>
                                <td class="px-5 py-3 text-gray-400">{{ $order->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">ยังไม่มีคำสั่งซื้อ</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top products --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <h2 class="font-semibold text-gray-900">สินค้าขายดี</h2>
            <ol class="mt-4 space-y-3.5">
                @forelse ($topProducts as $item)
                    <li class="flex items-center gap-3">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-xs font-bold text-primary-600">{{ $loop->iteration }}</span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-gray-800">{{ $item->product_name }}</p>
                            <p class="text-xs text-gray-400">ขายแล้ว {{ number_format((int) $item->qty_sold) }} ชิ้น</p>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">{{ money((float) $item->revenue) }}</span>
                    </li>
                @empty
                    <li class="text-sm text-gray-400">ยังไม่มีข้อมูลการขาย</li>
                @endforelse
            </ol>
        </div>
    </div>

    {{-- Low stock + latest customers --}}
    <div class="mt-6 grid gap-4 lg:grid-cols-2">
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <h2 class="flex items-center gap-2 font-semibold text-gray-900">
                สินค้าใกล้หมดสต็อก
                @if ($lowStockProducts->isNotEmpty())
                    <span class="rounded-full bg-red-50 px-2 py-0.5 text-xs font-bold text-red-500">{{ $lowStockProducts->count() }}</span>
                @endif
            </h2>
            <ul class="mt-4 space-y-3">
                @forelse ($lowStockProducts as $product)
                    <li class="flex items-center gap-3">
                        <img src="{{ $product->thumbnail_url }}" alt="" loading="lazy" class="h-10 w-10 rounded-lg object-cover">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-gray-800">{{ $product->name }}</p>
                            <p class="text-xs text-gray-400">SKU: {{ $product->sku }}</p>
                        </div>
                        <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $product->stock === 0 ? 'bg-red-50 text-red-500' : 'bg-amber-50 text-amber-600' }}">
                            เหลือ {{ $product->stock }}
                        </span>
                    </li>
                @empty
                    <li class="text-sm text-gray-400">สต็อกสินค้าทุกรายการอยู่ในระดับปกติ</li>
                @endforelse
            </ul>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <h2 class="font-semibold text-gray-900">ลูกค้าใหม่ล่าสุด</h2>
            <ul class="mt-4 space-y-3">
                @forelse ($latestCustomers as $customer)
                    <li class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-100 text-sm font-bold text-primary-600">
                            {{ mb_substr($customer->name, 0, 1) }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-gray-800">{{ $customer->name }}</p>
                            <p class="truncate text-xs text-gray-400">{{ $customer->email }}</p>
                        </div>
                        <span class="text-xs text-gray-400">{{ $customer->created_at->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="text-sm text-gray-400">ยังไม่มีลูกค้า</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-admin-layout>
