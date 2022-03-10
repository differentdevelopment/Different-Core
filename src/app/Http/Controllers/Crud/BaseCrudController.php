<?php

namespace Different\DifferentCore\app\Http\Controllers\Crud;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Different\DifferentCore\app\Http\Controllers\Traits\FileUpload;

class BaseCrudController extends CrudController
{
    use FileUpload;

    protected function store()
    {
        $this->handleFileUpload();
    }

    protected function update()
    {
        $this->handleFileUpload();
    }
}
