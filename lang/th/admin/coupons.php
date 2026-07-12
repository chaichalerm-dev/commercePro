<?php

return [

    'title' => 'คูปองส่วนลด',
    'total_count' => 'ทั้งหมด :count คูปอง',

    'table' => [
        'code' => 'โค้ด',
        'discount' => 'ส่วนลด',
        'min_order' => 'ขั้นต่ำ',
        'used_count' => 'ใช้ไปแล้ว',
        'expires_at' => 'หมดอายุ',
        'status' => 'สถานะ',
        'actions' => 'จัดการ',
        'edit' => 'แก้ไข',
        'delete' => 'ลบ',
    ],

    'no_expiry' => 'ไม่มีกำหนด',

    'status' => [
        'usable' => 'ใช้งานได้',
        'expired_or_full' => 'หมดอายุ/เต็ม',
        'disabled' => 'ปิดใช้งาน',
    ],

    'empty_state' => 'ยังไม่มีคูปอง',

    'confirm' => [
        'delete' => 'ลบคูปองนี้?',
    ],

    'form' => [
        'code_label' => 'โค้ดคูปอง',
        'type_label' => 'ประเภทส่วนลด',
        'value_label' => 'มูลค่าส่วนลด',
        'value_hint' => '(% หรือบาท ตามประเภท)',
        'min_order_label' => 'ยอดสั่งซื้อขั้นต่ำ (บาท)',
        'max_uses_label' => 'จำนวนครั้งสูงสุด',
        'max_uses_hint' => '(เว้นว่าง = ไม่จำกัด)',
        'is_active_label' => 'เปิดใช้งานคูปอง',
        'starts_at_label' => 'เริ่มใช้ได้',
        'expires_at_label' => 'หมดอายุ',
        'submit_create' => 'สร้างคูปอง',
        'save_changes' => 'บันทึกการแก้ไข',
        'cancel' => 'ยกเลิก',
    ],

    'page' => [
        'edit_title' => 'แก้ไขคูปอง: :code',
    ],

    'flash' => [
        'created' => 'สร้างคูปอง :code แล้ว',
        'updated' => 'บันทึกคูปอง :code แล้ว',
        'delete_blocked' => 'คูปอง :code เคยถูกใช้ในคำสั่งซื้อ จึงถูกปิดใช้งานแทนการลบ',
        'deleted' => 'ลบคูปอง :code แล้ว',
    ],

];
