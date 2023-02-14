<?php

namespace Different\DifferentCore\app\Http\Controllers\Cruds;

use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Different\DifferentCore\app\Models\File;
use Different\DifferentCore\app\Utils\Breadcrumb\BreadcrumbMenuItem;

class FilesCrudController extends BaseCrudController
{
    use ListOperation;

    public function setup()
    {
        crud_permission($this->crud, 'file-manage');

        $this->crud->setRoute(backpack_url('filemanager'));
        $this->crud->setEntityNameStrings(__('different-core::files.file'), __('different-core::files.files'));
        $this->crud->setModel(File::class);

        $this->data['breadcrumbs_menu'] = [
            new BreadcrumbMenuItem(
                backpack_url('dashboard'),
                __('backpack::crud.admin'),
                'las la-tachometer-alt',
            ),
        ];
    }

    public function setupListOperation()
    {
        //region Columns
        $this->crud->addColumn([
            'name' => 'link',
            'label' => __('different-core::files.link'),
            'type' => 'custom_html',
            'value' => function($entry) {
                return '<a href="' . $entry->getUrl() . '" target="_blank">' . $entry->uuid . '</a>';
            },
        ]);
        $this->crud->addColumn([
            'name' => 'original_name',
            'label' => __('different-core::files.original_name'),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'mime_type',
            'label' => __('different-core::files.mime_type'),
            'type' => 'text',
        ]);
        //endregion

        
        //region Filters
        $this->crud->addFilter([
            'name' => 'original_name',
            'type' => 'text',
            'label' => __('different-core::files.original_name'),
        ],
        false,
        function ($value) {
            $this->crud->addClause('where', 'original_name', 'like', '%'.$value.'%');
        });
        $this->crud->addFilter([
            'name' => 'mime_type',
            'type' => 'text',
            'label' => __('different-core::files.mime_type'),
        ],
        false,
        function ($value) {
            $this->crud->addClause('where', 'mime_type', 'like', '%'.$value.'%');
        });
        //endregion
    }
}
