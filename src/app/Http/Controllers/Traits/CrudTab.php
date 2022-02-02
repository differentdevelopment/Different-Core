<?php


namespace Different\DifferentCore\app\Http\Controllers\Traits;

use Illuminate\Support\Facades\View;

trait CrudTab
{
    public static function setTabs($passed_this, array $tabs = [], bool $show_on_list = false): void
    {
        if (!count($tabs)) return;

        throw_if(
            isset($passed_this->data['different_core_tabs']), 
            \Exception::class, 
            'The cruds tabs attribute is occupied, check Different\DifferentCore\app\Http\Controllers\Traits\CrudTab::setTabs method!'
        );

        View::share('different_core_tabs', $tabs);

        if ($show_on_list) $passed_this->crud->setListView('different-core::crudtab.tabbed.list');
        $passed_this->crud->setShowView('different-core::crudtab.tabbed.show');
        $passed_this->crud->setCreateView('different-core::crudtab.tabbed.create');
        $passed_this->crud->setEditView('different-core::crudtab.tabbed.edit');
    }
}
