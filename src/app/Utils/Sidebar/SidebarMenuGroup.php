<?php

namespace Different\DifferentCore\app\Utils\Sidebar;

class SidebarMenuGroup
{
    protected $title = "";
    protected $icon = "";
    protected $items = [];
    protected $permissions = null;

    function __construct($title, $items, $icon = "", $permissions = null)
    {
        $this->title = $title;
        $this->items = $items;
        $this->icon = $icon;
        $this->permissions = $permissions;
    }

    function render()
    {
        return view('different-core::sidebar.group', [
            'title' => $this->title,
            'items' => $this->items,
            'icon' => $this->icon,
            'permissions' => $this->permissions,
        ]);
    }

    public static function __set_state($an_array)
    {
        $obj = new SidebarMenuGroup(
            $an_array['title'],
            $an_array['items'],
            $an_array['icon'] ?? "",
            $an_array['permissions'] ?? null
        );
        return $obj;
    }
}
