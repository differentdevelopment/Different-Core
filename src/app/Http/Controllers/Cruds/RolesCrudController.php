<?php

namespace Different\DifferentCore\app\Http\Controllers\Cruds;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Different\DifferentCore\app\Http\Requests\Crud\Role\RoleStoreRequest;
use Different\DifferentCore\app\Http\Requests\Crud\Role\RoleUpdateRequest;
use Different\DifferentCore\app\Models\Permission;
use Different\DifferentCore\app\Models\Role;
use Different\DifferentCore\app\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class RolesCrudController extends BaseCrudController
{
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
        crud_permission($this->crud, 'role-manage');

        $this->permission_model = config('different-core.config.permissionmanager.models.permission');

        $this->crud->setModel(Role::class);
        $this->crud->setEntityNameStrings(trans('different-core::roles.role'), trans('different-core::roles.roles'));
        $this->crud->setRoute(backpack_url('role'));

        if (config('different-core.config.permissionmanager.allow_role_create') == false) {
            $this->crud->denyAccess('create');
        }
        if (config('different-core.config.permissionmanager.allow_role_update') == false) {
            $this->crud->denyAccess('update');
        }
        if (config('different-core.config.permissionmanager.allow_role_delete') == false) {
            $this->crud->denyAccess('delete');
        }
    }

    public function update()
    {
        User::query()
            ->whereHas('roles', function($query){
                $query->where('roles.id', request()->id);
            })
            ->each(function(User $user){
                Cache::forget('selectable_accounts_for_user_' . $user->id);
            });

        return parent::update();
    }

    public function destroy($id)
    {
        User::query()
            ->whereHas('roles', function($query) use ($id){
                $query->where('roles.id', $id);
            })
            ->each(function(User $user){
                Cache::forget('selectable_accounts_for_user_' . $user->id);
            });

        return $this->traitDestroy($id);
    }

    public function setupListOperation()
    {
        /**
         * Show a column for the name of the role.
         */
        $this->crud->addColumn([
            'name' => 'readable_name',
            'label' => trans('different-core::roles.name'),
            'type' => 'text',
        ]);

        /**
         * Show a column with the number of users that have that particular role.
         *
         * Note: To account for the fact that there can be thousands or millions
         * of users for a role, we did not use the `relationship_count` column,
         * but instead opted to append a fake `user_count` column to
         * the result, using Laravel's `withCount()` method.
         * That way, no users are loaded.
         */
        $this->crud->query->withCount('users');
        $this->crud->addColumn([
            'label' => trans('different-core::users.users'),
            'type' => 'text',
            'name' => 'users_count',
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('user?roles='.urlencode('["'.$entry->getKey().'"]'));
                },
            ],
            'suffix' => ' ' . __('different-core::users.user'),
        ]);

        /**
         * Show the exact permissions that role has.
         */
        $this->crud->addColumn([
            // n-n relationship (with pivot table)
            'label' => ucfirst(trans('different-core::permissions.permissions')),
            'type' => 'select_multiple',
            'name' => 'permissions', // the method that defines the relationship in your Model
            'entity' => 'permissions', // the method that defines the relationship in your Model
            'attribute' => 'readable_name', // foreign key attribute that is shown to role
            'model' => $this->permission_model, // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
        ]);
    }

    public function setupCreateOperation()
    {
        $this->crud->setValidation(RoleStoreRequest::class);
        $this->addFields();
        Cache::forget('spatie.permission.cache');
    }

    public function setupUpdateOperation()
    {
        $this->crud->setValidation(RoleUpdateRequest::class);
        $this->addFields();
        Cache::forget('spatie.permission.cache');
    }

    public function store()
    {
        $request = $this->crud->getRequest();
        $request->request->set('name', Str::slug($request->input('readable_name', '-')));
        $this->crud->setRequest($request);

        $this->crud->setValidation(RoleStoreRequest::class);
        $this->crud->setRequest($this->crud->validateRequest());

        return parent::store();
    }

    private function addFields()
    {
        $this->crud->addField([
            'name' => 'readable_name',
            'label' => trans('different-core::permissions.name'),
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => ucfirst(trans('different-core::permissions.permissions')),
            'type' => 'permissions',
            'name' => 'permissions',
            'permisisons' => Permission::query()->orderBy('group')->get(),
            'view_namespace' => 'different-core::fields',
        ]);

        $this->crud->addField([
            'name' => 'name',
            'type' => 'hidden',
        ]);
    }

    /*
     * Get an array list of all available guard types
     * that have been defined in app/config/auth.php
     *
     * @return array
     **/
    private function getGuardTypes()
    {
        $guards = config('auth.guards');

        $returnable = [];
        foreach ($guards as $key) {
            $returnable[$key] = $key;
        }

        return $returnable;
    }
}
