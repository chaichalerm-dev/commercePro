<?php

return [

    'order_status' => [
        'pending' => 'รอดำเนินการ',
        'processing' => 'กำลังเตรียมสินค้า',
        'shipped' => 'จัดส่งแล้ว',
        'delivered' => 'ส่งถึงแล้ว',
        'cancelled' => 'ยกเลิก',
    ],

    'payment_status' => [
        'unpaid' => 'ยังไม่ชำระเงิน',
        'paid' => 'ชำระเงินแล้ว',
        'refunded' => 'คืนเงินแล้ว',
    ],

    'product_status' => [
        'active' => 'เปิดขาย',
        'draft' => 'แบบร่าง',
        'archived' => 'เก็บเข้าคลัง',
    ],

    'coupon_type' => [
        'fixed' => 'ลดราคาคงที่',
        'percent' => 'ลดเป็นเปอร์เซ็นต์',
    ],

    'user_role' => [
        'owner' => 'เจ้าของระบบ',
        'admin' => 'ผู้ดูแลระบบ',
        'staff' => 'พนักงาน',
        'user' => 'ลูกค้า',
    ],

    'user_status' => [
        'active' => 'ใช้งานได้',
        'banned' => 'ถูกระงับ',
    ],

    'banner_position' => [
        'hero' => 'แบนเนอร์หลัก',
        'promo' => 'แบนเนอร์โปรโมชั่น',
    ],

];
