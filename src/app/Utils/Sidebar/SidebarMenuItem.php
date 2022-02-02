<?php

namespace Different\DifferentCore\app\Utils\Sidebar;

class SidebarMenuItem
{
    protected $url = "";
    protected $title = "";
    protected $icon = "";

    function __construct($url, $title, $icon = "") {
        $this->url = $url;
        $this->title = $title;
        $this->icon = $icon;
    }

    function render()
    {
        return view('different-core::sidebar.item', [
            'url' => $this->url,
            'title' => $this->title,
            'icon' => $this->icon,
        ]);
    }
}
