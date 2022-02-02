<?php

use Different\DifferentCore\app\Utils\Sidebar\SidebarMenuItem;
use Different\DifferentCore\app\Utils\Sidebar\SidebarMenuGroup;

return [
    'sidebar_menu' => [
        new SidebarMenuItem('dashboard', 'test'),
        new SidebarMenuItem('dashboard', 'dashboard'),
        new SidebarMenuGroup('dashboard', [
            new SidebarMenuItem('dashboard', 'dashboard'),
            new SidebarMenuItem('dashboard', 'dashboard'),
            new SidebarMenuItem('dashboard', 'dashboard')
        ])
    ],
];
