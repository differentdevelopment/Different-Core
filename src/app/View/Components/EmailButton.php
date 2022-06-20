<?php

namespace Different\DifferentCore\app\View\Components;

use Illuminate\View\Component;

class EmailButton extends Component
{
    public $href, $text, $background, $textColor;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($href, $text, $background = '#2e58ff', $textColor = '#fff')
    {
        $this->href = $href;
        $this->text = $text;
        $this->background = $background;
        $this->textColor = $textColor;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('different-core::email.components.button');
    }
}
