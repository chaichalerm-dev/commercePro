<x-mail::message>
# ขอบคุณสำหรับคำสั่งซื้อ!

สวัสดีคุณ {{ $order->user->name }} — เราได้รับคำสั่งซื้อ **{{ $order->order_number }}** ของคุณเรียบร้อยแล้ว

<x-mail::table>
| สินค้า | จำนวน | ราคา |
|:--|:-:|--:|
@foreach ($order->items as $item)
| {{ $item->product_name }} | {{ $item->qty }} | {{ money((float) $item->total) }} |
@endforeach
| **ค่าจัดส่ง** | | {{ money((float) $order->shipping) }} |
@if ((float) $order->discount > 0)
| **ส่วนลด** | | -{{ money((float) $order->discount) }} |
@endif
| **ยอดรวมทั้งสิ้น** | | **{{ money((float) $order->grand_total) }}** |
</x-mail::table>

**จัดส่งไปที่:** {{ $order->address?->recipient }}, {{ $order->address?->full_address }}

<x-mail::button :url="route('orders.show', $order)">
ดูรายละเอียดคำสั่งซื้อ
</x-mail::button>

ขอบคุณที่ช้อปกับเรา<br>
ทีมงาน {{ config('app.name') }}
</x-mail::message>
