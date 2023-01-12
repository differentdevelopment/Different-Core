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
    'login_logo' => null, //ha a login screenen más logo-t akarunk megjeleníteni
];
