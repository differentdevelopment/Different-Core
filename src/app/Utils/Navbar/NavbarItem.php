<?php

namespace Different\DifferentCore\app\Utils\Navbar;

class NavbarItem
{
    protected $url = "";
    protected $title = "";
    protected $permission = null;
    protected $active = false;
    protected $disabled = false;

    function __construct($url, $title, $permission = null, $active = false, $disabled = false) {
        $this->url = $url;
        $this->title = $title;
        $this->permission = $permission;
        $this->active = $active;
        $this->disabled = $disabled;
    }

    function render()
    {
        return view('different-core::navbar.item', [
            'url' => $this->url,
            'title' => $this->title,
            'permission' => $this->permission,
            'active' => $this->active,
            'disabled' => $this->disabled,
        ]);
    }
}
