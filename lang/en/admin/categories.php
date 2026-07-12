<?php

return [

    'fields' => [
        'name' => 'Category name',
        'image' => 'Image',
    ],

    'title' => 'Manage Categories',
    'total_count' => 'Total :count categories',

    'table' => [
        'category' => 'Category',
        'slug' => 'Slug',
        'products_count' => 'Products',
        'sort_order' => 'Order',
        'status' => 'Status',
        'actions' => 'Actions',
        'edit' => 'Edit',
        'delete' => 'Delete',
    ],

    'status' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ],

    'empty_state' => 'No categories yet',

    'confirm' => [
        'delete' => 'Delete this category?',
    ],

    'form' => [
        'slug' => 'Slug',
        'slug_hint' => '(leave empty to auto-generate)',
        'sort_order' => 'Sort order',
        'is_active_label' => 'Enable this category',
        'category_image_label' => 'Category image',
        'submit_create' => 'Add category',
        'save_changes' => 'Save changes',
        'cancel' => 'Cancel',
    ],

    'page' => [
        'create_title' => 'Add category',
        'edit_title' => 'Edit category: :name',
    ],

    'flash' => [
        'created' => 'Category ":name" added.',
        'updated' => 'Category ":name" saved.',
        'delete_blocked' => 'Cannot delete: category ":name" still has products.',
        'deleted' => 'Category ":name" deleted.',
    ],

];
