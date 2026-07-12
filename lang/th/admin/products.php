<?php

return [

    'fields' => [
        'category_id' => 'หมวดหมู่',
        'name' => 'ชื่อสินค้า',
        'price' => 'ราคา',
        'compare_at_price' => 'ราคาก่อนลด',
        'stock' => 'จำนวนสต็อก',
        'thumbnail' => 'รูปหลัก',
        'images' => 'รูปภาพ',
    ],

    'title' => 'จัดการสินค้า',

    'tabs' => [
        'all' => 'สินค้าทั้งหมด',
        'trash' => 'ถังขยะ',
    ],

    'filters' => [
        'search_placeholder' => 'ค้นหาชื่อสินค้า หรือ SKU...',
        'all_categories' => 'ทุกหมวดหมู่',
        'all_statuses' => 'ทุกสถานะ',
        'submit' => 'กรอง',
        'clear' => 'ล้างตัวกรอง',
    ],

    'table' => [
        'product' => 'สินค้า',
        'category' => 'หมวดหมู่',
        'price' => 'ราคา',
        'stock' => 'สต็อก',
        'status' => 'สถานะ',
        'featured' => 'แนะนำ',
        'actions' => 'จัดการ',
        'edit' => 'แก้ไข',
        'delete' => 'ลบ',
        'restore' => 'กู้คืน',
    ],

    'toggle_featured_title' => 'สลับสินค้าแนะนำ',
    'view_storefront_title' => 'ดูหน้าร้าน',

    'empty_state' => 'ไม่พบสินค้า',
    'empty_trash' => 'ถังขยะว่างเปล่า',

    'confirm' => [
        'delete' => 'ย้ายสินค้านี้ไปถังขยะ?',
    ],

    'form' => [
        'section_basic_info' => 'ข้อมูลสินค้า',
        'slug' => 'Slug',
        'slug_hint' => '(เว้นว่าง = สร้างอัตโนมัติ)',
        'sku' => 'SKU',
        'sku_hint' => '(เว้นว่าง = สร้างอัตโนมัติ)',
        'description' => 'รายละเอียดสินค้า',

        'section_pricing_stock' => 'ราคาและสต็อก',
        'selling_price' => 'ราคาขาย (บาท)',
        'compare_at_price_hint' => '(ถ้ามี)',

        'section_variants' => 'ตัวเลือกสินค้า (Variants)',
        'add_variant' => '+ เพิ่มตัวเลือก',
        'variant_name_label' => 'ประเภท (เช่น Size)',
        'variant_value_label' => 'ค่า (เช่น XL)',
        'variant_price_modifier_label' => 'บวกราคา (บาท)',
        'remove_variant' => 'ลบตัวเลือก',
        'no_variants' => 'สินค้านี้ไม่มีตัวเลือกย่อย (คลิก "+ เพิ่มตัวเลือก" หากต้องการ)',

        'section_publishing' => 'การเผยแพร่',
        'select_category_placeholder' => '— เลือกหมวดหมู่ —',
        'status_label' => 'สถานะ',
        'featured_label' => 'แสดงเป็นสินค้าแนะนำ',

        'images_hint_max' => '(สูงสุด 6 รูป)',
        'tick_to_remove_image' => 'ติ๊กเพื่อลบรูปนี้',
        'remove_image_note' => 'ติ๊กที่รูปเพื่อลบเมื่อบันทึก',

        'submit_create' => 'เพิ่มสินค้า',
        'save_changes' => 'บันทึกการแก้ไข',
        'cancel' => 'ยกเลิก',
    ],

    'page' => [
        'create_title' => 'เพิ่มสินค้า',
        'edit_title' => 'แก้ไขสินค้า: :name',
    ],

    'flash' => [
        'created' => 'เพิ่มสินค้า ":name" เรียบร้อยแล้ว',
        'updated' => 'บันทึกสินค้า ":name" เรียบร้อยแล้ว',
        'deleted' => 'ย้ายสินค้า ":name" ไปถังขยะแล้ว',
        'restored' => 'กู้คืนสินค้า ":name" เรียบร้อยแล้ว',
        'featured_on' => 'ตั้ง ":name" เป็นสินค้าแนะนำแล้ว',
        'featured_off' => 'นำ ":name" ออกจากสินค้าแนะนำแล้ว',
    ],

];
