<?php

namespace Different\DifferentCore\app\Utils\Sidebar;

class SidebarController
{
    public static function menus()
    {
        $default = [
            new SidebarMenuGroup(
                'different-core::sidebar.system',
                [
                    new SidebarMenuItem(route('admin.user.index'), 'different-core::users.users', 'las la-user', 'user-list'),
                    new SidebarMenuItem(route('admin.account.index'), 'different-core::accounts.accounts', 'las la-users', 'account-list'),
                    new SidebarMenuItem(route('admin.role.index'), 'different-core::roles.roles', 'las la-id-badge', 'role-manage'),
                    new SidebarMenuItem(route('admin.activity.index'), 'different-core::activities.activities', 'las la-history', 'activity-list'),
                    new SidebarMenuItem(route('admin.filemanager.index'), 'different-core::files.files', 'las la-folder', 'file-manage'),
                    new SidebarMenuItem(route('admin.settings'), 'different-core::settings.settings', 'las la-sliders-h', 'setting-manage'),
                ],
                'las la-cog',
                [
                    'user-list',
                    'role-manage',
                    'activity-list',
                    'setting-manage',
                ]
            ),
        ];

        return array_merge(
            config('different-core.config.sidebar_menu'),
            $default,
        );
    }
}
