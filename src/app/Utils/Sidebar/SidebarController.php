<?php

namespace Different\DifferentCore\app\Utils\Sidebar;

use Different\DifferentCore\app\Utils\Sidebar\SidebarMenuItem;
use Different\DifferentCore\app\Utils\Sidebar\SidebarMenuGroup;

class SidebarController
{
    public static function menus()
    {
        $default = [
            new SidebarMenuGroup(
                __('different-core::sidebar.system'),
                [
                    new SidebarMenuItem(backpack_url('user'), __('different-core::users.users'), 'las la-user', 'user-list'),
                    new SidebarMenuItem(backpack_url('role'), __('different-core::roles.roles'), 'las la-users', 'role-manage'),
                    new SidebarMenuItem(backpack_url('activity'), __('different-core::activities.activities'), 'las la-history', 'activity-list'),
                    new SidebarMenuItem(backpack_url('settings'), __('different-core::settings.settings'), 'las la-sliders-h', 'setting-manage'),
                ],
                'las la-cog',
                [
                    'user-list',
                    'role-manage',
                    'activity-list',
                    'setting-manage'
                ]
            ),
        ];

        return array_merge(
            config('different-core.sidebar_menu'),
            $default,
        );
    }
}
