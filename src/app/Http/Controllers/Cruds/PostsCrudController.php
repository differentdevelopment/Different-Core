<?php

namespace Different\DifferentCore\app\Http\Controllers\Cruds;

use Different\DifferentCore\app\Models\Post;
use Different\DifferentCore\app\Http\Controllers\Cruds\BaseCrudController;
use Different\DifferentCore\app\Http\Requests\Crud\Post\PostStoreRequest;
use Different\DifferentCore\app\Http\Requests\Crud\Post\PostUpdateRequest;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Different\DifferentCore\app\Utils\Breadcrumb\BreadcrumbMenuItem;

class PostsCrudController extends BaseCrudController
{
    use ShowOperation;
    use ListOperation;
    use CreateOperation {
        store as traitStore;
    }
    use UpdateOperation{
        update as traitUpdate;
    }
    use DeleteOperation{
        destroy as traitDestroy;
    }

    public function setup()
    {
        crud_permissions($this->crud, 'post');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/post');
        $this->crud->setEntityNameStrings(__('different-core::posts.post'), __('different-core::posts.posts'));
        $this->crud->setModel(Post::class);

        $this->data['breadcrumbs_menu'] = [
            new BreadcrumbMenuItem(
                backpack_url('dashboard'),
                __('backpack::crud.admin'),
                'las la-tachometer-alt',
            ),
        ];
    }

    protected function setupListOperation()
    {
        //region Columns
        $this->crud->addColumn([
            'name' => 'id',
            'label' => __('different-core::posts.id'),
            'type' => 'number',
        ]);
        $this->crud->addColumn([
            'name' => 'title',
            'label' => __('different-core::posts.title'),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'slug',
            'label' => __('different-core::posts.slug'),
            'type' => 'text',
        ]);
        //endregion

        //region Filters
        $this->crud->addFilter([
            'name' => 'title',
            'type' => 'text',
            'label' => __('different-core::posts.title'),
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'title', 'like', '%'.$value.'%');
            });
        $this->crud->addFilter([
            'name' => 'slug',
            'type' => 'text',
            'label' => __('different-core::posts.slug'),
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'slug', 'like', '%'.$value.'%');
            });
        //endregion

    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);
        $this->crud->setColumns([
            [
                'name' => 'id',
                'label' => __('different-core::posts.id'),
                'type' => 'number',
            ],
            [
                'name' => 'title',
                'label' => __('different-core::posts.title'),
                'type' => 'text',
            ],
            [
                'name' => 'slug',
                'label' => __('different-core::posts.slug'),
                'type' => 'text',
            ],
            [
                'name' => 'content',
                'label' => __('different-core::posts.content'),
                'type' => 'text',
            ]
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(PostStoreRequest::class);
        $this->addFields();
    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(PostUpdateRequest::class);
        $this->addFields();
        $this->crud->modifyField('slug',[
            'attributes' => null
        ]);
    }

    protected function addFields()
    {
        $this->crud->addField(
            [
            'name' => 'title',
            'label' => 'Title',
            'type' => 'text'
            ]); 
            $this->crud->addField([
            'name' => 'slug',
            'target' => 'title',
            'label' => 'Slug',
            'type' => 'slug',
            'attributes' => [
                'readonly' => 'readonly'
              ]
        ]);
        $this->crud->addField([
            'name' => 'content',
            'label' => 'Content',
            'type' => 'wysiwyg'
        ]);
    }

    public function store(){
        $this->crud->setValidation(PostStoreRequest::class);
        $this->crud->setRequest($this->crud->validateRequest());

        return parent::store();
    }

    public function update(){
        $this->crud->setValidation(PostUpdateRequest::class);
        $this->crud->setRequest($this->crud->validateRequest());

        return parent::update();
    }

}
