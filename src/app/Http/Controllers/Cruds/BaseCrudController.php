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

        if (isset($this->data['tabs']) && count($this->data['tabs'])) {
            $this->crud->setListView('different-core::crud.tabs.list');
            $this->crud->setShowView('different-core::crud.tabs.show');
            $this->crud->setCreateView('different-core::crud.tabs.create');
            $this->crud->setEditView('different-core::crud.tabs.edit');
        }
    }

    
    protected function addAccountIdFieldIfNeeded()
    {
        if ($this->isAccountBasedCrud()) {
            if (array_key_exists('account_id', $this->crud->getFields())) return;
            $this->crud->addField(['name' => 'account_id', 'type' => 'hidden', 'default' => session('account_id')]);
            $this->crud->getRequest()->request->add(['account_id' => session('account_id')]);
        }
    }

    protected function addUserIdField()
    {
        if (array_key_exists('user_id', $this->crud->getFields())) return;
        $this->crud->addField(['name' => 'user_id', 'type' => 'hidden', 'default' => backpack_user()->id]);
        $this->crud->getRequest()->request->add(['user_id' => backpack_user()->id]);
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

    protected function setupColumnsFieldsFromMethod(): void
    {
        $this->crud->setColumns($this->getColumns());
        $this->crud->addFields($this->getFields());
    }

    protected function setupFiltersFromMethod(): void
    {
        foreach($this->getFilters() as $filter) {
            $this->crud->addFilter($filter[0], $filter[1], $filter[2]);
        }
    }
}
