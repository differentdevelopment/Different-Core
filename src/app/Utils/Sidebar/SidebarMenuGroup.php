<?php

namespace Different\DifferentCore\app\Utils\Sidebar;

class SidebarMenuGroup
{
    protected $title = "";
    protected $icon = "";
    protected $items = [];

    function __construct($title, $items, $icon = "") {
        $this->title = $title;
        $this->icon = $icon;
        $this->items = $items;
    }

    function render()
    {
        return view('different-core::sidebar.group', [
            'title' => $this->title,
            'icon' => $this->icon,
            'items' => $this->items,
        ]);
    }
}
