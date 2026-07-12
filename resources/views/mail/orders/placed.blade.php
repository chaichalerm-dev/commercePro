<x-mail::message>
# {{ __('mail.order_placed.heading') }}

{{ __('mail.order_placed.greeting', ['name' => $order->user->name, 'order_number' => $order->order_number]) }}

<x-mail::table>
| {{ __('mail.order_placed.table_product') }} | {{ __('mail.order_placed.table_qty') }} | {{ __('mail.order_placed.table_price') }} |
|:--|:-:|--:|
@foreach ($order->items as $item)
| {{ $item->product_name }} | {{ $item->qty }} | {{ money((float) $item->total) }} |
@endforeach
| **{{ __('mail.order_placed.shipping') }}** | | {{ money((float) $order->shipping) }} |
@if ((float) $order->discount > 0)
| **{{ __('mail.order_placed.discount') }}** | | -{{ money((float) $order->discount) }} |
@endif
| **{{ __('mail.order_placed.grand_total') }}** | | **{{ money((float) $order->grand_total) }}** |
</x-mail::table>

**{{ __('mail.order_placed.shipping_to') }}** {{ $order->address?->recipient }}, {{ $order->address?->full_address }}

<x-mail::button :url="route('orders.show', $order)">
{{ __('mail.order_placed.cta') }}
</x-mail::button>

{{ __('mail.order_placed.thanks') }}<br>
{{ __('mail.order_placed.team', ['app' => config('app.name')]) }}
</x-mail::message>
