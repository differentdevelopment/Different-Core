<?php

namespace Different\DifferentCore\app\Http\Controllers\Cruds;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Different\DifferentCore\app\Http\Controllers\Traits\FileUpload;
use Illuminate\Support\Facades\Route;
use Different\DifferentCore\app\Http\Middlewares\AccountCheckMiddleware;

class BaseCrudController extends CrudController
{
    use FileUpload;

    protected function setupConfigurationForCurrentOperation()
    {
        parent::setupConfigurationForCurrentOperation();

        // Modell szinten kell kezelni, global scope
        /*if ($this->isAccountBasedCrud()) {
            $this->crud->query->where('account_id', session('account_id'));
        }*/
    }

    
    protected function addAccountIdFieldIfNeeded()
    {
        if ($this->isAccountBasedCrud()) {
            $this->crud->addField(['name' => 'account_id', 'type' => 'hidden', 'default' => session('account_id')]);
            $this->crud->getRequest()->request->add(['account_id' => session('account_id')]);
        }
    }

    protected function store()
    {
        $this->handleFileUpload();
        $this->addAccountIdFieldIfNeeded();
    }

    protected function update()
    {
        $this->handleFileUpload();
        $this->addAccountIdFieldIfNeeded();
    }

    protected function isAccountBasedCrud()
    {
        return in_array(AccountCheckMiddleware::class , Route::current()->gatherMiddleware());
    }
}
