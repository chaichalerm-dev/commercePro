<?php

return [

    'title' => 'Activity Logs',

    'filter_placeholder' => 'กรองตาม action เช่น product',
    'filter_submit' => 'กรอง',
    'filter_clear' => 'ล้าง',

    'table' => [
        'time' => 'เวลา',
        'user' => 'ผู้ใช้',
        'action' => 'การกระทำ',
        'details' => 'รายละเอียด',
        'ip' => 'IP',
    ],

    'system_user' => 'ระบบ',
    'empty_state' => 'ยังไม่มีบันทึกกิจกรรม',

    'actions' => [
        'banner' => [
            'created' => 'เพิ่มแบนเนอร์ใหม่',
            'updated' => 'แก้ไขแบนเนอร์',
            'deleted' => 'ลบแบนเนอร์',
        ],
        'category' => [
            'created' => 'เพิ่มหมวดหมู่ใหม่',
            'updated' => 'แก้ไขหมวดหมู่',
            'deleted' => 'ลบหมวดหมู่',
        ],
        'coupon' => [
            'created' => 'สร้างคูปองใหม่',
            'updated' => 'แก้ไขคูปอง',
            'deleted' => 'ลบคูปอง',
        ],
        'review' => [
            'moderated' => 'ตรวจสอบรีวิว',
            'deleted' => 'ลบรีวิว',
        ],
        'settings' => [
            'updated' => 'แก้ไขการตั้งค่าเว็บไซต์',
        ],
        'user' => [
            'role_changed' => 'เปลี่ยนบทบาทผู้ใช้งาน',
            'status_changed' => 'เปลี่ยนสถานะผู้ใช้งาน',
        ],
        'order' => [
            'placed' => 'มีคำสั่งซื้อใหม่',
            'cancelled' => 'ยกเลิกคำสั่งซื้อ',
            'status_changed' => 'เปลี่ยนสถานะคำสั่งซื้อ',
            'payment_changed' => 'เปลี่ยนสถานะการชำระเงิน',
        ],
        'product' => [
            'created' => 'เพิ่มสินค้าใหม่',
            'updated' => 'แก้ไขสินค้า',
            'deleted' => 'ลบสินค้า',
            'restored' => 'กู้คืนสินค้า',
            'featured_toggled' => 'สลับสถานะสินค้าแนะนำ',
        ],
    ],

    'details' => [
        'title' => 'หัวข้อ: ":title"',
        'name' => 'ชื่อ: ":name"',
        'code' => 'รหัสคูปอง: :code',
        'review_approved' => 'อนุมัติรีวิวสินค้า ":product"',
        'review_hidden' => 'ซ่อนรีวิวสินค้า ":product"',
        'review_deleted' => 'ลบรีวิวของสินค้า ":product"',
        'settings_updated' => 'แก้ไขการตั้งค่า :count รายการ',
        'role_changed' => 'เปลี่ยนบทบาทของ ":name" เป็น :role',
        'banned' => 'ระงับการใช้งานของ ":name"',
        'unbanned' => 'ปลดระงับการใช้งานของ ":name"',
        'order_placed' => 'คำสั่งซื้อ #:number มูลค่า :total',
        'order_cancelled' => 'ยกเลิกคำสั่งซื้อ #:number',
        'order_status_changed' => 'คำสั่งซื้อ #:number เปลี่ยนสถานะเป็น ":status"',
        'order_payment_changed' => 'คำสั่งซื้อ #:number เปลี่ยนสถานะการชำระเงินเป็น ":status"',
        'product_updated' => 'แก้ไขสินค้า ":name" (เปลี่ยน: :fields)',
        'featured_on' => 'ตั้งเป็นสินค้าแนะนำ',
        'featured_off' => 'เอาออกจากสินค้าแนะนำ',
        'none' => '-',
    ],

];
