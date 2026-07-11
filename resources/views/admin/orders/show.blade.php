<x-admin-layout :title="'คำสั่งซื้อ '.$order->order_number">
    <div class="grid gap-4 lg:grid-cols-3">
        <div class="space-y-4 lg:col-span-2">
            {{-- Items --}}
            <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                <h2 class="border-b border-gray-100 p-5 font-semibold text-gray-900">รายการสินค้า</h2>
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-400">
                        <tr>
                            <th class="px-5 py-3 font-medium">สินค้า</th>
                            <th class="px-5 py-3 font-medium">ราคา/ชิ้น</th>
                            <th class="px-5 py-3 font-medium">จำนวน</th>
                            <th class="px-5 py-3 text-right font-medium">รวม</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        @if ($item->product)
                                            <img src="{{ $item->product->thumbnail_url }}" alt="" loading="lazy" class="h-10 w-10 rounded-lg object-cover">
                                        @endif
                                        <span class="font-medium text-gray-800">{{ $item->product_name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-gray-500">{{ money((float) $item->price) }}</td>
                                <td class="px-5 py-3 text-gray-500">{{ $item->qty }}</td>
                                <td class="px-5 py-3 text-right font-semibold">{{ money((float) $item->total) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t border-gray-100 text-sm">
                        <tr><td colspan="3" class="px-5 pt-4 text-right text-gray-500">ยอดรวมสินค้า</td><td class="px-5 pt-4 text-right">{{ money((float) $order->subtotal) }}</td></tr>
                        @if ((float) $order->discount > 0)
                            <tr><td colspan="3" class="px-5 pt-1 text-right text-emerald-600">ส่วนลด @if($order->coupon)({{ $order->coupon->code }})@endif</td><td class="px-5 pt-1 text-right text-emerald-600">-{{ money((float) $order->discount) }}</td></tr>
                        @endif
                        <tr><td colspan="3" class="px-5 pt-1 text-right text-gray-500">ค่าจัดส่ง</td><td class="px-5 pt-1 text-right">{{ money((float) $order->shipping) }}</td></tr>
                        <tr><td colspan="3" class="px-5 py-4 text-right font-semibold">ยอดรวมทั้งสิ้น</td><td class="px-5 py-4 text-right text-lg font-bold text-primary-600">{{ money((float) $order->grand_total) }}</td></tr>
                    </tfoot>
                </table>
            </section>

            {{-- Customer + address --}}
            <section class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <h2 class="font-semibold text-gray-900">ลูกค้า</h2>
                    <p class="mt-2 text-sm">
                        <span class="font-medium text-gray-800">{{ $order->user->name }}</span><br>
                        <span class="text-gray-500">{{ $order->user->email }}</span><br>
                        @if ($order->user->phone)<span class="text-gray-500">{{ $order->user->phone }}</span>@endif
                    </p>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <h2 class="font-semibold text-gray-900">ที่อยู่จัดส่ง</h2>
                    @if ($order->address)
                        <p class="mt-2 text-sm text-gray-600">
                            <span class="font-medium text-gray-800">{{ $order->address->recipient }}</span> · {{ $order->address->phone }}<br>
                            {{ $order->address->full_address }}
                        </p>
                    @else
                        <p class="mt-2 text-sm text-gray-400">ไม่มีข้อมูลที่อยู่</p>
                    @endif
                </div>
            </section>
        </div>

        {{-- Status management --}}
        <aside class="h-fit space-y-4">
            <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <h2 class="font-semibold text-gray-900">สถานะคำสั่งซื้อ</h2>
                <p class="mt-3"><span class="rounded-full px-3 py-1.5 text-sm font-medium {{ $order->status->color() }}">{{ $order->status->label() }}</span></p>

                @if ($order->status->transitions() !== [])
                    <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mt-4">
                        @csrf @method('PATCH')
                        <label for="status" class="block text-sm font-medium text-gray-700">เปลี่ยนสถานะเป็น</label>
                        <select id="status" name="status" class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                            @foreach ($order->status->transitions() as $next)
                                <option value="{{ $next->value }}">{{ $next->label() }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="mt-3 w-full rounded-xl bg-primary-500 py-2.5 text-sm font-semibold text-white shadow transition hover:bg-primary-600">อัปเดตสถานะ</button>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </form>
                    <p class="mt-2 text-xs text-gray-400">การยกเลิกจะคืนสต็อกสินค้าอัตโนมัติ</p>
                @else
                    <p class="mt-3 text-xs text-gray-400">คำสั่งซื้อนี้อยู่ในสถานะสุดท้ายแล้ว</p>
                @endif
            </section>

            <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <h2 class="font-semibold text-gray-900">การชำระเงิน</h2>
                <p class="mt-3"><span class="rounded-full px-3 py-1.5 text-sm font-medium {{ $order->payment_status->color() }}">{{ $order->payment_status->label() }}</span></p>
                <form method="POST" action="{{ route('admin.orders.payment', $order) }}" class="mt-4">
                    @csrf @method('PATCH')
                    <select name="payment_status" class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                        @foreach ($paymentStatuses as $status)
                            <option value="{{ $status->value }}" @selected($order->payment_status === $status)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="mt-3 w-full rounded-xl bg-gray-900 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-700">อัปเดตการชำระเงิน</button>
                </form>
            </section>

            <section class="rounded-2xl border border-gray-100 bg-white p-5 text-sm shadow-sm">
                <h2 class="font-semibold text-gray-900">ข้อมูลเพิ่มเติม</h2>
                <dl class="mt-3 space-y-1.5 text-gray-500">
                    <div class="flex justify-between"><dt>วันที่สั่งซื้อ</dt><dd>{{ $order->created_at->format('d/m/Y H:i') }}</dd></div>
                    <div class="flex justify-between"><dt>อัปเดตล่าสุด</dt><dd>{{ $order->updated_at->format('d/m/Y H:i') }}</dd></div>
                </dl>
                <a href="{{ route('admin.orders.index') }}" class="mt-4 block text-center text-sm text-primary-600 hover:underline">← กลับรายการคำสั่งซื้อ</a>
            </section>
        </aside>
    </div>
</x-admin-layout>
