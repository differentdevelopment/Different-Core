<?php

namespace Different\DifferentCore\app\View\Components;

use Illuminate\View\Component;

class EmailContent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('different-core::email.components.content');
    }
}
