<?php

return [

    'title' => 'Manage Orders',

    'filters' => [
        'search_placeholder' => 'Search order number or customer name...',
        'all_statuses' => 'All statuses',
        'all_payment_statuses' => 'All payment statuses',
        'submit' => 'Filter',
        'clear' => 'Clear filters',
    ],

    'table' => [
        'order_number' => 'Order #',
        'customer' => 'Customer',
        'items' => 'Items',
        'total' => 'Total',
        'status' => 'Status',
        'payment' => 'Payment',
        'date' => 'Date',
        'actions' => 'Actions',
        'view_details' => 'View details',
    ],

    'empty_state' => 'No orders found',

    'show' => [
        'title' => 'Order :order_number',
        'items_title' => 'Order items',

        'table' => [
            'product' => 'Product',
            'unit_price' => 'Price/unit',
            'qty' => 'Qty',
            'total' => 'Total',
        ],

        'subtotal' => 'Subtotal',
        'discount' => 'Discount',
        'shipping' => 'Shipping',
        'grand_total' => 'Grand total',

        'customer_title' => 'Customer',
        'shipping_address_title' => 'Shipping address',
        'no_address' => 'No address on file',

        'status_title' => 'Order status',
        'change_status_label' => 'Change status to',
        'update_status_button' => 'Update status',
        'cancel_note' => 'Cancelling automatically restocks the items.',
        'final_status_note' => 'This order is already in its final status.',

        'payment_title' => 'Payment',
        'update_payment_button' => 'Update payment',

        'more_info_title' => 'Additional information',
        'ordered_at' => 'Order date',
        'updated_at' => 'Last updated',
        'back_link' => '← Back to orders',
    ],

    'flash' => [
        'status_updated' => 'Order status updated to ":status".',
        'payment_updated' => 'Payment status updated.',
    ],

];
