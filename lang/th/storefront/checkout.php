<?php

return [

    'fields' => [
        'recipient' => 'ชื่อผู้รับ',
        'phone' => 'เบอร์โทรศัพท์',
        'line1' => 'ที่อยู่',
        'district' => 'เขต/อำเภอ',
        'province' => 'จังหวัด',
        'postal_code' => 'รหัสไปรษณีย์',
    ],

    'title' => 'ชำระเงิน',

    'breadcrumb' => [
        'cart' => 'ตะกร้าสินค้า',
        'checkout' => 'ชำระเงิน',
    ],

    'demo_notice' => 'ระบบเดโม — ไม่มีการตัดเงินจริง',

    'address_heading' => 'ที่อยู่จัดส่ง',
    'default_badge' => 'ค่าเริ่มต้น',
    'use_new_address' => '+ ใช้ที่อยู่ใหม่',
    'use_existing_address' => '← ใช้ที่อยู่เดิม',
    'address_line1_label' => 'ที่อยู่ (บ้านเลขที่ ถนน)',

    'items_heading' => 'รายการสินค้า (:count ชิ้น)',

    'coupon_heading' => 'คูปองส่วนลด',
    'coupon_placeholder' => 'เช่น WELCOME10',
    'coupon_apply' => 'ใช้',
    'coupon_remove' => 'นำออก',

    'summary_heading' => 'สรุปยอด',
    'subtotal' => 'ยอดรวมสินค้า',
    'discount' => 'ส่วนลดคูปอง',
    'shipping' => 'ค่าจัดส่ง',
    'free' => 'ฟรี',
    'grand_total' => 'ยอดชำระทั้งหมด',
    'place_order' => 'ยืนยันคำสั่งซื้อ (เดโม)',

    'default_address_label' => 'ที่อยู่จัดส่ง',

    'flash' => [
        'cart_empty' => 'ตะกร้าสินค้าของคุณว่างเปล่า',
        'coupon_applied' => 'ใช้คูปอง :code แล้ว',
        'coupon_invalid' => 'คูปองไม่ถูกต้อง หมดอายุ หรือยอดสั่งซื้อไม่ถึงขั้นต่ำ',
        'coupon_removed' => 'นำคูปองออกแล้ว',
        'order_placed' => 'สั่งซื้อสำเร็จ! หมายเลขคำสั่งซื้อของคุณคือ :number',
    ],

    'errors' => [
        'cart_empty' => 'ตะกร้าสินค้าของคุณว่างเปล่า',
        'product_unavailable' => 'สินค้า ":name" ไม่พร้อมจำหน่ายแล้ว กรุณานำออกจากตะกร้า',
        'insufficient_stock' => 'สินค้า ":name" เหลือเพียง :available ชิ้น กรุณาปรับจำนวนในตะกร้า',
        'order_not_cancellable' => 'คำสั่งซื้อนี้ไม่สามารถยกเลิกได้แล้ว',
        'invalid_status_transition' => 'เปลี่ยนสถานะจาก :from เป็น :to ไม่ได้',
        'coupon_not_applicable' => 'คูปองนี้ใช้ไม่ได้กับคำสั่งซื้อของคุณ',
    ],

];
