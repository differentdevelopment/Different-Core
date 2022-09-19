<?php

use Different\DifferentCore\app\Utils\Sidebar\SidebarMenuItem;

return [
    'sidebar_menu' => [
        new SidebarMenuItem(
            '/admin/dashboard',
            'backpack::base.dashboard',
            'las la-home'
        ),
    ],
    'magic_link_login' => false,
    'account_selector_enabled' => false,
    'user_account_cache_ttl' => 3600,
];
