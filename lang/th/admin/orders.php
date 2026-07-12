<?php

return [

    'title' => 'จัดการคำสั่งซื้อ',

    'filters' => [
        'search_placeholder' => 'ค้นหาเลขที่ออเดอร์ หรือชื่อลูกค้า...',
        'all_statuses' => 'ทุกสถานะ',
        'all_payment_statuses' => 'การชำระเงินทั้งหมด',
        'submit' => 'กรอง',
        'clear' => 'ล้างตัวกรอง',
    ],

    'table' => [
        'order_number' => 'เลขที่',
        'customer' => 'ลูกค้า',
        'items' => 'รายการ',
        'total' => 'ยอดรวม',
        'status' => 'สถานะ',
        'payment' => 'ชำระเงิน',
        'date' => 'วันที่',
        'actions' => 'จัดการ',
        'view_details' => 'ดูรายละเอียด',
    ],

    'empty_state' => 'ไม่พบคำสั่งซื้อ',

    'show' => [
        'title' => 'คำสั่งซื้อ :order_number',
        'items_title' => 'รายการสินค้า',

        'table' => [
            'product' => 'สินค้า',
            'unit_price' => 'ราคา/ชิ้น',
            'qty' => 'จำนวน',
            'total' => 'รวม',
        ],

        'subtotal' => 'ยอดรวมสินค้า',
        'discount' => 'ส่วนลด',
        'shipping' => 'ค่าจัดส่ง',
        'grand_total' => 'ยอดรวมทั้งสิ้น',

        'customer_title' => 'ลูกค้า',
        'shipping_address_title' => 'ที่อยู่จัดส่ง',
        'no_address' => 'ไม่มีข้อมูลที่อยู่',

        'status_title' => 'สถานะคำสั่งซื้อ',
        'change_status_label' => 'เปลี่ยนสถานะเป็น',
        'update_status_button' => 'อัปเดตสถานะ',
        'cancel_note' => 'การยกเลิกจะคืนสต็อกสินค้าอัตโนมัติ',
        'final_status_note' => 'คำสั่งซื้อนี้อยู่ในสถานะสุดท้ายแล้ว',

        'payment_title' => 'การชำระเงิน',
        'update_payment_button' => 'อัปเดตการชำระเงิน',

        'more_info_title' => 'ข้อมูลเพิ่มเติม',
        'ordered_at' => 'วันที่สั่งซื้อ',
        'updated_at' => 'อัปเดตล่าสุด',
        'back_link' => '← กลับรายการคำสั่งซื้อ',
    ],

    'flash' => [
        'status_updated' => 'อัปเดตสถานะเป็น ":status" แล้ว',
        'payment_updated' => 'อัปเดตสถานะการชำระเงินแล้ว',
    ],

];
