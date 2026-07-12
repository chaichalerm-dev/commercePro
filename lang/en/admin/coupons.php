<?php

return [

    'title' => 'Discount Coupons',
    'total_count' => 'Total :count coupons',

    'table' => [
        'code' => 'Code',
        'discount' => 'Discount',
        'min_order' => 'Minimum',
        'used_count' => 'Used',
        'expires_at' => 'Expires',
        'status' => 'Status',
        'actions' => 'Actions',
        'edit' => 'Edit',
        'delete' => 'Delete',
    ],

    'no_expiry' => 'No expiry',

    'status' => [
        'usable' => 'Usable',
        'expired_or_full' => 'Expired/full',
        'disabled' => 'Disabled',
    ],

    'empty_state' => 'No coupons yet',

    'confirm' => [
        'delete' => 'Delete this coupon?',
    ],

    'form' => [
        'code_label' => 'Coupon code',
        'type_label' => 'Discount type',
        'value_label' => 'Discount value',
        'value_hint' => '(% or THB, depending on type)',
        'min_order_label' => 'Minimum order (THB)',
        'max_uses_label' => 'Maximum uses',
        'max_uses_hint' => '(leave empty = unlimited)',
        'is_active_label' => 'Enable this coupon',
        'starts_at_label' => 'Valid from',
        'expires_at_label' => 'Expires',
        'submit_create' => 'Create coupon',
        'save_changes' => 'Save changes',
        'cancel' => 'Cancel',
    ],

    'page' => [
        'edit_title' => 'Edit coupon: :code',
    ],

    'flash' => [
        'created' => 'Coupon :code created.',
        'updated' => 'Coupon :code saved.',
        'delete_blocked' => 'Coupon :code has already been used on orders, so it was disabled instead of deleted.',
        'deleted' => 'Coupon :code deleted.',
    ],

];
