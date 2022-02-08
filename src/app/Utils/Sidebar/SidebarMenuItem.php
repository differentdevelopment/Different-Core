<?php

namespace Different\DifferentCore\app\Utils\Sidebar;

class SidebarMenuItem
{
    protected $url = "";
    protected $title = "";
    protected $icon = "";
    protected $permission = null;

    function __construct($url, $title, $icon = "", $permission = null) {
        $this->url = $url;
        $this->title = $title;
        $this->icon = $icon;
        $this->permission = $permission;
    }

    function render()
    {
        return view('different-core::sidebar.item', [
            'url' => $this->url,
            'title' => $this->title,
            'icon' => $this->icon,
            'permission' => $this->permission,
        ]);
    }
}
