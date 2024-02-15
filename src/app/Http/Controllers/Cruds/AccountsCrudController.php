<?php

namespace Different\DifferentCore\app\Http\Controllers\Cruds;

use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Different\DifferentCore\app\Http\Requests\Crud\Account\AccountRequest;
use Different\DifferentCore\app\Models\Account;
use Different\DifferentCore\app\Utils\Breadcrumb\BreadcrumbMenuItem;
use Different\DifferentCore\app\Models\User;
use Illuminate\Support\Facades\Cache;

class AccountsCrudController extends BaseCrudController
{
    use ListOperation;

    // use ShowOperation;
    use CreateOperation {
        store as traitStore;
    }
    use UpdateOperation {
        update as traitUpdate;
    }
    use DeleteOperation {
        destroy as traitDestroy;
    }

    public function setup()
    {
        crud_permissions($this->crud, 'account');
        $this->crud->setRoute(backpack_url('account'));
        $this->crud->setEntityNameStrings(__('different-core::accounts.account'), __('different-core::accounts.accounts'));
        $this->crud->setModel(Account::class);

        if (! $this->crud->getRequest()->order) {
            $this->crud->orderBy('name', 'asc');
        }
    }

    protected function setupListOperation()
    {
        //region Oszlopok
        $this->crud->addColumn([
            'name' => 'id',
            'label' => __('different-core::accounts.id'),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'name',
            'label' => __('different-core::accounts.name'),
            'type' => 'text',
        ]);
        //endregion

        //region Szűrők
        $this->crud->addFilter([
            'name' => 'name',
            'type' => 'text',
            'label' => __('different-core::accounts.name'),
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'name', 'like', '%'.$value.'%');
            });
        //endregion
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(AccountRequest::class);
        $this->addFields();
    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(AccountRequest::class);
        $this->addFields();
    }

    protected function addFields()
    {
        //region Mezők
        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => __('different-core::users.name'),
                'type' => 'text',
            ],
        ]);
        //endregion
    }

    public function store()
    {
        User::query()->each(function(User $user){
            Cache::forget('selectable_accounts_for_user_' . $user->id);
        });

        return parent::store();
    }

    public function update()
    {
        User::query()->each(function(User $user){
            Cache::forget('selectable_accounts_for_user_' . $user->id);
        });

        return parent::update();
    }

    public function destroy($id)
    {
        User::query()->each(function(User $user){
            Cache::forget('selectable_accounts_for_user_' . $user->id);
        });

        return $this->traitDestroy($id);
    }

}
