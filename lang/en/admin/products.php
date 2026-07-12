<?php

return [

    'fields' => [
        'category_id' => 'Category',
        'name' => 'Product name',
        'price' => 'Price',
        'compare_at_price' => 'Compare-at price',
        'stock' => 'Stock',
        'thumbnail' => 'Main image',
        'images' => 'Images',
    ],

    'title' => 'Manage Products',

    'tabs' => [
        'all' => 'All products',
        'trash' => 'Trash',
    ],

    'filters' => [
        'search_placeholder' => 'Search by product name or SKU...',
        'all_categories' => 'All categories',
        'all_statuses' => 'All statuses',
        'submit' => 'Filter',
        'clear' => 'Clear filters',
    ],

    'table' => [
        'product' => 'Product',
        'category' => 'Category',
        'price' => 'Price',
        'stock' => 'Stock',
        'status' => 'Status',
        'featured' => 'Featured',
        'actions' => 'Actions',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'restore' => 'Restore',
    ],

    'toggle_featured_title' => 'Toggle featured product',
    'view_storefront_title' => 'View on storefront',

    'empty_state' => 'No products found',
    'empty_trash' => 'Trash is empty',

    'confirm' => [
        'delete' => 'Move this product to trash?',
    ],

    'form' => [
        'section_basic_info' => 'Product information',
        'slug' => 'Slug',
        'slug_hint' => '(leave empty to auto-generate)',
        'sku' => 'SKU',
        'sku_hint' => '(leave empty to auto-generate)',
        'description' => 'Product description',

        'section_pricing_stock' => 'Pricing & stock',
        'selling_price' => 'Selling price (THB)',
        'compare_at_price_hint' => '(if any)',

        'section_variants' => 'Product variants',
        'add_variant' => '+ Add variant',
        'variant_name_label' => 'Type (e.g. Size)',
        'variant_value_label' => 'Value (e.g. XL)',
        'variant_price_modifier_label' => 'Price add-on (THB)',
        'remove_variant' => 'Remove variant',
        'no_variants' => 'This product has no variants (click "+ Add variant" if needed)',

        'section_publishing' => 'Publishing',
        'select_category_placeholder' => '— Select a category —',
        'status_label' => 'Status',
        'featured_label' => 'Show as featured product',

        'images_hint_max' => '(up to 6 images)',
        'tick_to_remove_image' => 'Tick to remove this image',
        'remove_image_note' => 'Tick an image to remove it when you save',

        'submit_create' => 'Add product',
        'save_changes' => 'Save changes',
        'cancel' => 'Cancel',
    ],

    'page' => [
        'create_title' => 'Add product',
        'edit_title' => 'Edit product: :name',
    ],

    'flash' => [
        'created' => 'Product ":name" added.',
        'updated' => 'Product ":name" saved.',
        'deleted' => 'Product ":name" moved to trash.',
        'restored' => 'Product ":name" restored.',
        'featured_on' => '":name" set as a featured product.',
        'featured_off' => '":name" removed from featured products.',
    ],

];
