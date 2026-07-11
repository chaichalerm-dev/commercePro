<x-storefront-layout title="ชำระเงิน">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <x-breadcrumb :items="['ตะกร้าสินค้า' => route('cart.index'), 'ชำระเงิน' => null]" />
        <h1 class="mt-4 text-2xl font-bold text-gray-900">ชำระเงิน</h1>
        <p class="mt-1 text-sm text-amber-600">⚠ ระบบเดโม — ไม่มีการตัดเงินจริง</p>

        <x-input-error :messages="$errors->get('cart')" class="mt-3" />

        <form method="POST" action="{{ route('checkout.place') }}" class="mt-6 grid gap-6 lg:grid-cols-[1fr_360px]"
              x-data="{ addressMode: '{{ $addresses->isNotEmpty() ? 'existing' : 'new' }}' }">
            @csrf

            <div class="space-y-4">
                {{-- Address --}}
                <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <h2 class="font-semibold text-gray-900">ที่อยู่จัดส่ง</h2>

                    @if ($addresses->isNotEmpty())
                        <div class="mt-3 space-y-2" x-show="addressMode === 'existing'">
                            @foreach ($addresses as $address)
                                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 p-3.5 transition has-[:checked]:border-primary-400 has-[:checked]:bg-primary-50/50">
                                    <input type="radio" name="address_id" value="{{ $address->id }}" @checked($loop->first)
                                           class="mt-0.5 border-gray-300 text-primary-500 focus:ring-primary-400">
                                    <span class="text-sm">
                                        <span class="font-medium text-gray-800">{{ $address->recipient }} · {{ $address->phone }}</span>
                                        @if ($address->is_default)<span class="ml-1 rounded bg-primary-100 px-1.5 py-0.5 text-[10px] font-bold text-primary-600">ค่าเริ่มต้น</span>@endif
                                        <span class="mt-0.5 block text-gray-500">{{ $address->full_address }}</span>
                                    </span>
                                </label>
                            @endforeach
                            <button type="button" @click="addressMode = 'new'" class="text-sm font-medium text-primary-600 hover:underline">+ ใช้ที่อยู่ใหม่</button>
                        </div>
                    @endif

                    <div class="mt-3 grid gap-3 sm:grid-cols-2" x-show="addressMode === 'new'" x-cloak>
                        <template x-if="addressMode === 'new'"><input type="hidden" name="address_id" value=""></template>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ชื่อผู้รับ</label>
                            <input type="text" name="recipient" value="{{ old('recipient') }}" class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                            <x-input-error :messages="$errors->get('recipient')" class="mt-1" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">เบอร์โทรศัพท์</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                            <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">ที่อยู่ (บ้านเลขที่ ถนน)</label>
                            <input type="text" name="line1" value="{{ old('line1') }}" class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                            <x-input-error :messages="$errors->get('line1')" class="mt-1" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">เขต/อำเภอ</label>
                            <input type="text" name="district" value="{{ old('district') }}" class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                            <x-input-error :messages="$errors->get('district')" class="mt-1" />
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">จังหวัด</label>
                                <input type="text" name="province" value="{{ old('province') }}" class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                                <x-input-error :messages="$errors->get('province')" class="mt-1" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">รหัสไปรษณีย์</label>
                                <input type="text" name="postal_code" value="{{ old('postal_code') }}" class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                                <x-input-error :messages="$errors->get('postal_code')" class="mt-1" />
                            </div>
                        </div>
                        @if ($addresses->isNotEmpty())
                            <button type="button" @click="addressMode = 'existing'" class="text-left text-sm font-medium text-primary-600 hover:underline">← ใช้ที่อยู่เดิม</button>
                        @endif
                    </div>
                </section>

                {{-- Items --}}
                <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <h2 class="font-semibold text-gray-900">รายการสินค้า ({{ $items->sum('qty') }} ชิ้น)</h2>
                    <ul class="mt-3 divide-y divide-gray-50">
                        @foreach ($items as $item)
                            <li class="flex items-center gap-3 py-3">
                                <img src="{{ $item->product->thumbnail_url }}" alt="" loading="lazy" class="h-14 w-14 rounded-lg object-cover">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-gray-800">{{ $item->product->name }}</p>
                                    <p class="text-xs text-gray-400">
                                        @if ($item->variant){{ $item->variant->name }}: {{ $item->variant->value }} · @endif
                                        x{{ $item->qty }}
                                    </p>
                                </div>
                                <span class="text-sm font-semibold">{{ money($item->line_total) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>
            </div>

            {{-- Summary --}}
            <aside class="h-fit space-y-4 lg:sticky lg:top-32">
                <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <h2 class="font-semibold text-gray-900">คูปองส่วนลด</h2>
                    @if ($coupon)
                        <div class="mt-3 flex items-center justify-between rounded-xl bg-emerald-50 px-3.5 py-2.5">
                            <span class="text-sm font-semibold text-emerald-700">{{ $coupon->code }}</span>
                            <button type="submit" form="remove-coupon" class="text-xs text-red-500 hover:underline">นำออก</button>
                        </div>
                    @else
                        <div class="mt-3 flex gap-2">
                            <input type="text" form="apply-coupon" name="code" placeholder="เช่น WELCOME10"
                                   class="w-full rounded-xl border-gray-200 text-sm uppercase focus:border-primary-400 focus:ring-primary-400">
                            <button type="submit" form="apply-coupon" class="shrink-0 rounded-xl bg-gray-900 px-4 text-sm font-medium text-white transition hover:bg-gray-700">ใช้</button>
                        </div>
                    @endif
                </section>

                <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <h2 class="font-semibold text-gray-900">สรุปยอด</h2>
                    <dl class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between"><dt class="text-gray-500">ยอดรวมสินค้า</dt><dd class="font-medium">{{ money($subtotal) }}</dd></div>
                        @if ($discount > 0)
                            <div class="flex justify-between text-emerald-600"><dt>ส่วนลดคูปอง</dt><dd>-{{ money($discount) }}</dd></div>
                        @endif
                        <div class="flex justify-between"><dt class="text-gray-500">ค่าจัดส่ง</dt><dd class="font-medium">{{ $shipping > 0 ? money($shipping) : 'ฟรี' }}</dd></div>
                    </dl>
                    <div class="mt-4 flex justify-between border-t border-gray-100 pt-4">
                        <span class="font-semibold">ยอดชำระทั้งหมด</span>
                        <span class="text-xl font-bold text-primary-600">{{ money($grandTotal) }}</span>
                    </div>
                    <button type="submit" class="mt-5 w-full rounded-xl bg-primary-500 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-primary-600">
                        ยืนยันคำสั่งซื้อ (เดโม)
                    </button>
                    <x-input-error :messages="$errors->get('coupon')" class="mt-2" />
                </section>
            </aside>
        </form>

        {{-- Coupon forms live outside the checkout form to avoid nesting --}}
        <form id="apply-coupon" method="POST" action="{{ route('checkout.coupon') }}">@csrf</form>
        <form id="remove-coupon" method="POST" action="{{ route('checkout.coupon.remove') }}">@csrf @method('DELETE')</form>
    </div>
</x-storefront-layout>
