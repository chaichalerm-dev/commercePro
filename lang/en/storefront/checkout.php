<?php

return [

    'fields' => [
        'recipient' => 'Recipient name',
        'phone' => 'Phone number',
        'line1' => 'Address',
        'district' => 'District',
        'province' => 'Province',
        'postal_code' => 'Postal code',
    ],

    'title' => 'Checkout',

    'breadcrumb' => [
        'cart' => 'Cart',
        'checkout' => 'Checkout',
    ],

    'demo_notice' => 'Demo system — no real charges will be made',

    'address_heading' => 'Shipping Address',
    'default_badge' => 'Default',
    'use_new_address' => '+ Use a new address',
    'use_existing_address' => '← Use existing address',
    'address_line1_label' => 'Address (house number, street)',

    'items_heading' => 'Order Items (:count items)',

    'coupon_heading' => 'Discount Coupon',
    'coupon_placeholder' => 'e.g. WELCOME10',
    'coupon_apply' => 'Apply',
    'coupon_remove' => 'Remove',

    'summary_heading' => 'Order Total',
    'subtotal' => 'Subtotal',
    'discount' => 'Coupon Discount',
    'shipping' => 'Shipping',
    'free' => 'Free',
    'grand_total' => 'Total Amount',
    'place_order' => 'Confirm Order (Demo)',

    'default_address_label' => 'Shipping address',

    'flash' => [
        'cart_empty' => 'Your cart is empty.',
        'coupon_applied' => 'Coupon :code applied.',
        'coupon_invalid' => 'Invalid or expired coupon, or order total below minimum.',
        'coupon_removed' => 'Coupon removed.',
        'order_placed' => 'Order placed successfully! Your order number is :number',
    ],

    'errors' => [
        'cart_empty' => 'Your cart is empty.',
        'product_unavailable' => 'Product ":name" is no longer available. Please remove it from your cart.',
        'insufficient_stock' => 'Only :available left of ":name". Please adjust the quantity in your cart.',
        'order_not_cancellable' => 'This order can no longer be cancelled.',
        'invalid_status_transition' => 'Cannot change status from :from to :to.',
        'coupon_not_applicable' => 'This coupon cannot be applied to your order.',
    ],

];
