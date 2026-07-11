<x-storefront-layout :title="'คำสั่งซื้อ '.$order->order_number">
    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">
        <x-breadcrumb :items="['คำสั่งซื้อของฉัน' => route('orders.index'), $order->order_number => null]" />

        <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $order->order_number }}</h1>
                <p class="mt-1 text-sm text-gray-400">สั่งซื้อเมื่อ {{ $order->created_at->format('d/m/Y H:i') }} น.</p>
            </div>
            <div class="flex gap-2">
                <span class="rounded-full px-3 py-1.5 text-sm font-medium {{ $order->status->color() }}">{{ $order->status->label() }}</span>
                <span class="rounded-full px-3 py-1.5 text-sm font-medium {{ $order->payment_status->color() }}">{{ $order->payment_status->label() }}</span>
            </div>
        </div>

        <div class="mt-6 grid gap-4 lg:grid-cols-[1fr_320px]">
            <div class="space-y-4">
                <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <h2 class="border-b border-gray-100 p-5 font-semibold text-gray-900">รายการสินค้า</h2>
                    <ul class="divide-y divide-gray-50 px-5">
                        @foreach ($order->items as $item)
                            <li class="flex items-center gap-3 py-3.5">
                                @if ($item->product)
                                    <img src="{{ $item->product->thumbnail_url }}" alt="" loading="lazy" class="h-14 w-14 rounded-lg object-cover">
                                @else
                                    <div class="flex h-14 w-14 items-center justify-center rounded-lg bg-gray-100 text-gray-300">—</div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    @if ($item->product)
                                        <a href="{{ route('products.show', $item->product->slug) }}" class="truncate text-sm font-medium text-gray-800 hover:text-primary-600">{{ $item->product_name }}</a>
                                    @else
                                        <p class="truncate text-sm font-medium text-gray-800">{{ $item->product_name }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400">{{ money((float) $item->price) }} × {{ $item->qty }}</p>
                                </div>
                                <span class="text-sm font-semibold">{{ money((float) $item->total) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>

                @if ($order->address)
                    <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <h2 class="font-semibold text-gray-900">ที่อยู่จัดส่ง</h2>
                        <p class="mt-2 text-sm text-gray-600">
                            <span class="font-medium text-gray-800">{{ $order->address->recipient }}</span> · {{ $order->address->phone }}<br>
                            {{ $order->address->full_address }}
                        </p>
                    </section>
                @endif
            </div>

            <aside class="h-fit rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <h2 class="font-semibold text-gray-900">สรุปยอด</h2>
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">ยอดรวมสินค้า</dt><dd>{{ money((float) $order->subtotal) }}</dd></div>
                    @if ((float) $order->discount > 0)
                        <div class="flex justify-between text-emerald-600">
                            <dt>ส่วนลด @if($order->coupon)({{ $order->coupon->code }})@endif</dt>
                            <dd>-{{ money((float) $order->discount) }}</dd>
                        </div>
                    @endif
                    <div class="flex justify-between"><dt class="text-gray-500">ค่าจัดส่ง</dt><dd>{{ (float) $order->shipping > 0 ? money((float) $order->shipping) : 'ฟรี' }}</dd></div>
                </dl>
                <div class="mt-4 flex justify-between border-t border-gray-100 pt-4">
                    <span class="font-semibold">ยอดรวมทั้งสิ้น</span>
                    <span class="text-xl font-bold text-primary-600">{{ money((float) $order->grand_total) }}</span>
                </div>

                @can('cancel', $order)
                    <form method="POST" action="{{ route('orders.cancel', $order) }}" class="mt-5"
                          onsubmit="return confirm('ยืนยันการยกเลิกคำสั่งซื้อนี้?')">
                        @csrf
                        <button type="submit" class="w-full rounded-xl border border-red-200 py-2.5 text-sm font-semibold text-red-500 transition hover:bg-red-50">
                            ยกเลิกคำสั่งซื้อ
                        </button>
                    </form>
                @endcan
            </aside>
        </div>
    </div>
</x-storefront-layout>
