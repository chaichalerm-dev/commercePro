<?php

return [

    'title' => 'Activity Logs',

    'filter_placeholder' => 'Filter by action, e.g. product',
    'filter_submit' => 'Filter',
    'filter_clear' => 'Clear',

    'table' => [
        'time' => 'Time',
        'user' => 'User',
        'action' => 'Action',
        'details' => 'Details',
        'ip' => 'IP',
    ],

    'system_user' => 'System',
    'empty_state' => 'No activity logged yet',

    'actions' => [
        'banner' => [
            'created' => 'Added a new banner',
            'updated' => 'Edited a banner',
            'deleted' => 'Deleted a banner',
        ],
        'category' => [
            'created' => 'Added a new category',
            'updated' => 'Edited a category',
            'deleted' => 'Deleted a category',
        ],
        'coupon' => [
            'created' => 'Created a new coupon',
            'updated' => 'Edited a coupon',
            'deleted' => 'Deleted a coupon',
        ],
        'review' => [
            'moderated' => 'Moderated a review',
            'deleted' => 'Deleted a review',
        ],
        'settings' => [
            'updated' => 'Updated site settings',
        ],
        'user' => [
            'role_changed' => 'Changed a user\'s role',
            'status_changed' => 'Changed a user\'s status',
        ],
        'order' => [
            'placed' => 'New order placed',
            'cancelled' => 'Cancelled an order',
            'status_changed' => 'Changed an order\'s status',
            'payment_changed' => 'Changed an order\'s payment status',
        ],
        'product' => [
            'created' => 'Added a new product',
            'updated' => 'Edited a product',
            'deleted' => 'Deleted a product',
            'restored' => 'Restored a product',
            'featured_toggled' => 'Toggled featured status',
        ],
    ],

    'details' => [
        'title' => 'Title: ":title"',
        'name' => 'Name: ":name"',
        'code' => 'Coupon code: :code',
        'review_approved' => 'Approved a review on ":product"',
        'review_hidden' => 'Hid a review on ":product"',
        'review_deleted' => 'Deleted a review on ":product"',
        'settings_updated' => 'Changed :count setting(s)',
        'role_changed' => 'Changed ":name"\'s role to :role',
        'banned' => 'Banned ":name"',
        'unbanned' => 'Unbanned ":name"',
        'order_placed' => 'Order #:number worth :total',
        'order_cancelled' => 'Cancelled order #:number',
        'order_status_changed' => 'Order #:number changed to ":status"',
        'order_payment_changed' => 'Order #:number payment changed to ":status"',
        'product_updated' => 'Edited product ":name" (changed: :fields)',
        'featured_on' => 'Set as a featured product',
        'featured_off' => 'Removed from featured products',
        'none' => '-',
    ],

];
