<?php

namespace Different\DifferentCore\app\Http\Controllers\Pages;

class DocumentationPageController
{
    public function index()
    {
        return view('different-core::pages.documentation', [
            'title' => __('different-core::documentation.documentation'),
        ]);
    }
}
