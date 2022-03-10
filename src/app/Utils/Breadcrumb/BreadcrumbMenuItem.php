<?php

namespace Different\DifferentCore\app\Utils\Breadcrumb;

class BreadcrumbMenuItem
{
    protected $url = "";
    protected $title = "";
    protected $icon = "";
    protected $permission = null;
    protected $disabled = false;

    function __construct($url, $title, $icon = "", $permission = null, $disabled = false) {
        $this->url = $url;
        $this->title = $title;
        $this->icon = $icon;
        $this->permission = $permission;
        $this->disabled = $disabled;
    }

    function render()
    {
        return view('different-core::breadcrumb.item', [
            'url' => $this->url,
            'title' => $this->title,
            'icon' => $this->icon,
            'permission' => $this->permission,
            'disabled' => $this->disabled,
        ]);
    }
}
