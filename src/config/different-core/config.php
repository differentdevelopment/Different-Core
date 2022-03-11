<?php

use Different\DifferentCore\app\Utils\Sidebar\SidebarMenuItem;
use Different\DifferentCore\app\Utils\Sidebar\SidebarMenuGroup;

return [
    'sidebar_menu' => [
        new SidebarMenuItem(
            backpack_url('dashboard'),
            'backpack::base.dashboard',
            'las la-home'
        ),
    ],
];
