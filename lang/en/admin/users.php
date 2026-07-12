<?php

return [

    'title' => 'Users',

    'filters' => [
        'search_placeholder' => 'Search by name or email...',
        'all_roles' => 'All roles',
        'submit' => 'Filter',
    ],

    'table' => [
        'user' => 'User',
        'role' => 'Role',
        'status' => 'Status',
        'joined_at' => 'Joined',
        'actions' => 'Actions',
        'you_label' => '(you)',
    ],

    'actions' => [
        'ban' => 'Ban account',
        'unban' => 'Unban',
    ],

    'confirm' => [
        'ban' => 'Ban this account?',
        'unban' => 'Unban this account?',
    ],

    'empty_state' => 'No users found',

    'flash' => [
        'cannot_change_own_role' => 'You cannot change your own role.',
        'role_changed' => "Changed :name's role to :role.",
        'cannot_ban_self' => 'You cannot ban your own account.',
        'banned' => ':name has been banned.',
        'unbanned' => ':name has been unbanned.',
    ],

];
