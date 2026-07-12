<?php

return [

    'fields' => [
        'name' => 'ชื่อหมวดหมู่',
        'image' => 'รูปภาพ',
    ],

    'title' => 'จัดการหมวดหมู่',
    'total_count' => 'ทั้งหมด :count หมวดหมู่',

    'table' => [
        'category' => 'หมวดหมู่',
        'slug' => 'Slug',
        'products_count' => 'จำนวนสินค้า',
        'sort_order' => 'ลำดับ',
        'status' => 'สถานะ',
        'actions' => 'จัดการ',
        'edit' => 'แก้ไข',
        'delete' => 'ลบ',
    ],

    'status' => [
        'active' => 'เปิดใช้งาน',
        'inactive' => 'ปิดใช้งาน',
    ],

    'empty_state' => 'ยังไม่มีหมวดหมู่',

    'confirm' => [
        'delete' => 'ลบหมวดหมู่นี้?',
    ],

    'form' => [
        'slug' => 'Slug',
        'slug_hint' => '(เว้นว่าง = สร้างอัตโนมัติ)',
        'sort_order' => 'ลำดับการแสดง',
        'is_active_label' => 'เปิดใช้งานหมวดหมู่นี้',
        'category_image_label' => 'รูปภาพหมวดหมู่',
        'submit_create' => 'เพิ่มหมวดหมู่',
        'save_changes' => 'บันทึกการแก้ไข',
        'cancel' => 'ยกเลิก',
    ],

    'page' => [
        'create_title' => 'เพิ่มหมวดหมู่',
        'edit_title' => 'แก้ไขหมวดหมู่: :name',
    ],

    'flash' => [
        'created' => 'เพิ่มหมวดหมู่ ":name" เรียบร้อยแล้ว',
        'updated' => 'บันทึกหมวดหมู่ ":name" เรียบร้อยแล้ว',
        'delete_blocked' => 'ลบไม่ได้: หมวดหมู่ ":name" ยังมีสินค้าอยู่',
        'deleted' => 'ลบหมวดหมู่ ":name" เรียบร้อยแล้ว',
    ],

];
