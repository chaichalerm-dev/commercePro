<?php

return [

    'title' => 'ผู้ใช้งาน',

    'filters' => [
        'search_placeholder' => 'ค้นหาชื่อ หรืออีเมล...',
        'all_roles' => 'ทุกบทบาท',
        'submit' => 'กรอง',
    ],

    'table' => [
        'user' => 'ผู้ใช้',
        'role' => 'บทบาท',
        'status' => 'สถานะ',
        'joined_at' => 'สมัครเมื่อ',
        'actions' => 'จัดการ',
        'you_label' => '(คุณ)',
    ],

    'actions' => [
        'ban' => 'ระงับบัญชี',
        'unban' => 'ปลดระงับ',
    ],

    'confirm' => [
        'ban' => 'ระงับบัญชีนี้?',
        'unban' => 'ปลดระงับบัญชีนี้?',
    ],

    'empty_state' => 'ไม่พบผู้ใช้',

    'flash' => [
        'cannot_change_own_role' => 'ไม่สามารถเปลี่ยนบทบาทของตัวเองได้',
        'role_changed' => 'เปลี่ยนบทบาทของ :name เป็น :role แล้ว',
        'cannot_ban_self' => 'ไม่สามารถระงับบัญชีของตัวเองได้',
        'banned' => 'ระงับบัญชี :name แล้ว',
        'unbanned' => 'ปลดระงับบัญชี :name แล้ว',
    ],

];
