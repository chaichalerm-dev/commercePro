<?php

return [

    'title' => 'Dashboard',

    'cards' => [
        'revenue' => 'Total revenue',
        'orders' => 'Total orders',
        'pending_orders' => 'Pending orders',
        'products' => 'Products',
        'customers' => 'Customers',
    ],

    'revenue_chart_title' => 'Revenue — last 30 days',
    'status_chart_title' => 'Orders by status',

    'recent_orders' => [
        'title' => 'Recent orders',
        'empty' => 'No orders yet',
    ],

    'table' => [
        'order_number' => 'Order #',
        'customer' => 'Customer',
        'total' => 'Total',
        'status' => 'Status',
        'payment' => 'Payment',
        'date' => 'Date',
    ],

    'top_products' => [
        'title' => 'Best-selling products',
        'sold_qty' => 'Sold :qty pcs',
        'empty' => 'No sales data yet',
    ],

    'low_stock' => [
        'title' => 'Low stock products',
        'remaining' => 'Left :stock',
        'empty' => 'All products are at a healthy stock level',
    ],

    'latest_customers' => [
        'title' => 'Newest customers',
        'empty' => 'No customers yet',
    ],

];
