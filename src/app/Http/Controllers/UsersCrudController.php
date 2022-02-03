<?php

namespace Different\DifferentCore\app\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Different\DifferentCore\app\Http\Controllers\Traits\CrudTab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Different\DifferentCore\app\Models\User;
use Different\DifferentCore\app\Models\Permission;
use Different\DifferentCore\app\Models\Role;

class UsersCrudController extends CrudController
{

    use CrudTab;
    use ShowOperation {
        show as traitShow;
    }
    use CreateOperation {
        store as traitStore;
    }
    use UpdateOperation {
        update as traitUpdate;
    }
    use ListOperation;
    use FetchOperation;

    public function setup()
    {
        crud_permissions($this->crud, 'user');

        $this->crud->setRoute(backpack_url('user'));
        $this->crud->setEntityNameStrings(__('different-core::users.user'), __('different-core::users.users'));
        $this->crud->setModel(User::class);
        // $this->crud->addButton('line', 'verify', 'view', 'dwfw::crud.buttons.users.verify', 'beginning');

        $this->setupColumnsFieldsFromMethod();
        // $this->setupFiltersFromMethod();
        /*if (config('dwfw.user_list_global', true)) {
            // Ez mi a pék
            User::withoutGlobalScope(AccountScope::class)->get();
        }

        if (config('dwfw.simple_account_user_pivot', false)) {
            $this->crud
                ->addField([
                    'name' => 'accounts',
                    'label' => __('dwfw::accounts.accounts'),
                    'type' => 'select2_multiple',
                ])
                ->afterField('email');
        }*/

        /*CrudTab::setTabs($this, [
            new NavbarItem("#", "User", null, true, false),
            new NavbarItem(url('permissions'), "Permissions", null, false, false),
            new NavbarItem("#", "Roles", null, false, true),
        ], true);*/
    }

    public function show($id)
    {
        /*activity()
            ->causedBy(backpack_user())
            ->performedOn(User::find($id))
            ->log('The subject name is :subject.name, the causer name is :causer.name.');*/

        if (view()->exists('admin.users.show')) {
            $this->crud->hasAccessOrFail('list');
            $user = User::find($id);
            $this->data['crud'] = $this->crud;
            $this->data['user'] = $user;
            return view('admin.users.show', $this->data);
        } else {
            return $this->traitShow($id);
        }
    }

    /*public function fetchUser()
    {
        return $this->fetch([
            'model' => User::class,
            'searchable_attributes' => ['name', 'email'],
            'paginate' => 10,
        ]);
    }*/

    public function store()
    {
        $this->crud->setValidation(
            class_exists(\App\Http\Requests\UserStoreRequest::class)
                ? \App\Http\Requests\UserStoreRequest::class
                : \Different\DifferentCore\app\Http\Requests\UserStoreRequest::class
        );
        $this->crud->setRequest($this->crud->validateRequest());
        $this->crud->setRequest($this->handlePasswordInput($this->crud->getRequest()));
        $this->crud->unsetValidation();

        return $this->traitStore();
    }

    public function update()
    {
        $this->crud->setValidation(
            class_exists(\App\Http\Requests\UserUpdateRequest::class)
                ? \App\Http\Requests\UserUpdateRequest::class
                : \Different\DifferentCore\app\Http\Requests\UserUpdateRequest::class
        );
        $this->crud->setRequest($this->crud->validateRequest());
        $this->crud->setRequest($this->handlePasswordInput($this->crud->getRequest()));
        $this->crud->unsetValidation();

        return $this->traitUpdate();
    }

    protected function setupListOperation()
    {
        $this->crud->setColumns([
            [
                'name' => 'name',
                'label' => __('backpack::permissionmanager.name'),
                'type' => 'text',
            ],
            [
                'name' => 'email',
                'label' => __('backpack::permissionmanager.email'),
                'type' => 'email',
            ],
            [ // n-n relationship (with pivot table)
                'label' => __('backpack::permissionmanager.roles'), // Table column heading
                'type' => 'select_multiple',
                'name' => 'roles', // the method that defines the relationship in your Model
                'entity' => 'roles', // the method that defines the relationship in your Model
                'attribute' => 'readable_name', // foreign key attribute that is shown to user
                'model' => config('permission.models.role'), // foreign key model
            ],
            /*[ // n-n relationship (with pivot table)
                'label' => __('backpack::permissionmanager.extra_permissions'), // Table column heading
                'type' => 'select_multiple',
                'name' => 'permissions', // the method that defines the relationship in your Model
                'entity' => 'permissions', // the method that defines the relationship in your Model
                'attribute' => 'display_name', // foreign key attribute that is shown to user
                'model' => config('permission.models.permission'), // foreign key model
            ],
            [
                'name' => 'partner',
                'label' => __('dwfw::partners.partner'),
                'type' => 'select',
                'entity' => 'partner',
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhereHas('partner', function ($q) use ($column, $searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('contact_name', 'like', '%' . $searchTerm . '%');
                    });
                },
            ],
            [
                'name' => 'email_verified_at',
                'label' => __('dwfw::users.verified_at'),
                'type' => 'date',
            ],*/
        ]);
    }

    protected function setupColumnsFieldsFromMethod()
    {
        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => __('backpack::permissionmanager.name'),
                'type' => 'text',
            ],
            [
                'name' => 'email',
                'label' => __('backpack::permissionmanager.email'),
                'type' => 'email',
            ],
            [
                'name' => 'password',
                'label' => __('backpack::permissionmanager.password'),
                'type' => 'password',
            ],
            [
                'name' => 'password_confirmation',
                'label' => __('backpack::permissionmanager.password_confirmation'),
                'type' => 'password',
            ],
            [   
                // two interconnected entities
                'label' => __('backpack::permissionmanager.user_role_permission'),
                'field_unique_name' => 'user_role_permission',
                'type' => 'roles',
                'view_namespace' => 'different-core::fields',
                'name' => ['roles', 'permissions'],
                'primary_query' => function ($query) {
                    // if (!backpack_user()->hasRole('super admin')) {
                    //    $query->where('name', '<>', 'super admin');
                    // }
                },
                'subfields' => [
                    'primary' => [
                        'label' => __('backpack::permissionmanager.roles'),
                        'name' => 'roles', // the method that defines the relationship in your Model
                        'entity' => 'roles', // the method that defines the relationship in your Model
                        'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
                        'attribute' => 'readable_name', // foreign key attribute that is shown to user
                        'secondary_attribute' => 'name', // foreign key attribute that is shown to user
                        'model' => config('permission.models.role'), // foreign key model
                        'pivot' => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns' => 4, //can be 1,2,3,4,6
                        'order_by' => 'id',
                        'hasGroup' => false,
                    ],
                    'secondary' => [
                        'label' => ucfirst(__('backpack::permissionmanager.permission_singular')),
                        'name' => 'permissions', // the method that defines the relationship in your Model
                        'entity' => 'permissions', // the method that defines the relationship in your Model
                        'entity_primary' => 'roles', // the method that defines the relationship in your Model
                        'attribute' => 'name', // foreign key attribute that is shown to user
                        'secondary_attribute' => 'readable_name', // foreign key attribute that is shown to user
                        'model' => config('permission.models.permission'), // foreign key model
                        'pivot' => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns' => 3, //can be 1,2,3,4,6
                        'order_by' => 'group',
                        'hasGroup' => true,
                    ],
                ],
            ],
            /*[
                'label' => __('backpack::permissionmanager.user_role_permission'),
                'type' => 'roles',
                'field_unique_name' => 'user_role_permission',
                'name' => ['roles', 'permissions'],
                'permisisons' => Permission::query()->orderBy("group")->get(),
                'roles' => Role::query()->orderBy("name")->get(),
                'view_namespace' => 'different-core::fields',
            ]*/
            /*[
                'name' => 'separator',
                'type' => 'custom_html',
                'value' => '<a target="blank" href="' . backpack_url('permission') . '"><i class="fas fa-info-circle"></i> ' . __('backpack::permissionmanager.permission_descriptions') . '</a>'
            ],
            [
                'name' => 'partner_id',
                'label' => __('dwfw::partners.partner'),
                'type' => 'select',
                'entity' => 'partner',
                'attribute' => 'name_contact_name',
                'options' => (function ($query) {
                    return $query->orderBy('name', 'ASC')->orderBy('contact_name', 'ASC')->get();
                }),
                'wrapper' => [
                    'class' => 'form-group col-12 col-sm-12',
                ],
            ],

            [
                'name' => 'email_verified_at',
                'label' => __('dwfw::users.verified_at'),
                'type' => 'date',
                'wrapper' => [
                    'class' => 'form-group col-12 col-sm-6',
                ],
            ],
            [
                'name' => 'timezone_id',
                'label' => __('dwfw::timezones.timezone'),
                'type' => 'select',
                'entity' => 'timezone',
                'attribute' => 'name_with_diff',
                'model' => 'Different\Dwfw\app\Models\TimeZone',
                'options' => (function ($query) {
                    return $query->orderBy('name', 'ASC')->get();
                }),
                'default' => TimeZone::DEFAULT_TIMEZONE_CODE,
                'wrapper' => [
                    'class' => 'form-group col-12 col-sm-6',
                ],
            ],
            [
                'name' => 'last_device',
                'label' => __('dwfw::users.last_device'),
                'type' => 'text',
                'wrapper' => [
                    'class' => 'form-group col-12 col-sm-6',
                ],
            ],

            [
                'name' => 'profile_image',
                'label' => __('dwfw::users.profile_image'),
                'type' => 'image',
                'upload' => true,
                'wrapper' => [
                    'class' => 'form-group col-12 col-sm-6',
                ],
            ],
            [
                'name' => 'phone',
                'label' => __('dwfw::users.phone'),
                'type' => 'text',
            ],*/

        ]);
    }

    protected function getFilters()
    {
        return [
            [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => __('dwfw::users.name'),
                ],
                false,
                function ($value) {
                    $this->crud->addClause('where', 'name', 'like', '%' . $value . '%');
                },
            ],
            [
                [
                    'name' => 'email',
                    'type' => 'text',
                    'label' => __('dwfw::users.email'),
                ],
                false,
                function ($value) {
                    $this->crud->addClause('where', 'email', 'like', '%' . $value . '%');
                },
            ],
            [
                [
                    'name' => 'role',
                    'type' => 'select2_multiple',
                    'label' => __('dwfw::users.roles'),
                ],
                function () {
                    return Role::all()->pluck('display_name', 'id')->toArray();
                },
                function ($values) {
                    $values = explode(',', preg_replace('/[^\d|,]/', '', $values));
                    $this->crud->query->whereHas('roles', function (Builder $query) use ($values) {
                        $query->wherein('id', $values);
                    });
                }
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOM NON-BACKPACK METHODS
    |--------------------------------------------------------------------------
    */
    
    /**
     * Handle password input fields.
     */
    protected function handlePasswordInput($request)
    {
        // Remove fields not present on the user.
        $request->request->remove('password_confirmation');
        $request->request->remove('roles_show');
        $request->request->remove('permissions_show');

        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->set('password', Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }

        return $request;
    }
}
