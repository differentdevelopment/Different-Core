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
use Different\DifferentCore\app\Utils\Navbar\NavbarItem;
use Different\DifferentCore\app\Models\Activity;

class ActivitiesCrudController extends CrudController
{

    use CrudTab;
    // use ColumnFaker;
    use ShowOperation {
        show as traitShow;
    }
    // use LoggableAdmin;
    use ListOperation;
    use FetchOperation;

    public function setup()
    {
        /*if (!backpack_user()->can('manage users')) {
            $this->crud->denyAccess(['list', 'show', 'create', 'update', 'delete']);
        }*/

        $this->crud->setRoute(backpack_url('activity'));
        $this->crud->setEntityNameStrings(__('different-core::activities.activity'), __('different-core::activities.activities'));
        $this->crud->setModel(Activity::class);
        // $this->crud->addButton('line', 'verify', 'view', 'dwfw::crud.buttons.users.verify', 'beginning');

        // $this->setupColumnsFieldsFromMethod();
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
        /*activity()->log('Look, I logged something');
        if (view()->exists('admin.users.show')) {
            $this->crud->hasAccessOrFail('list');
            $user = User::find($id);
            $this->data['crud'] = $this->crud;
            $this->data['user'] = $user;
            return view('admin.users.show', $this->data);
        } else {
            return $this->traitShow($id);
        }*/
        return $this->traitShow($id);
    }

    public function fetchUser()
    {
        return $this->fetch([
            'model' => User::class,
            'searchable_attributes' => ['name', 'email'],
            'paginate' => 10,
        ]);
    }

    protected function setupListOperation()
    {
        $this->crud->setColumns([
            [
                'name' => 'created_at',
                'label' => __('different-core::activities.created_at'),
                'type' => 'datetime',
            ],
            /*[
                'name' => 'log_name',
                'label' => __('different-core::activities.log_name'),
                'type' => 'text',
            ],*/
            [
                'name' => 'description',
                'label' => __('different-core::activities.description'),
                'type' => 'text',
            ],
            [
                'name' => 'causer',
                'label' => __('different-core::activities.causer'),
                'type' => 'closure',
                'function' => function($entry) {
                    if ($entry->causer === null) {
                        return '';
                    }

                    return '<a href="' . backpack_url('users/' . $entry->causer->id . '/show') . '">' . $entry->causer->name . '</a>';
                }
            ],
            [
                'name' => 'subject_type',
                'label' => __('different-core::activities.subject_type'),
                'type' => 'text',
            ],
            [
                'name' => 'subject_id',
                'label' => __('different-core::activities.subject_id'),
                'type' => 'text',
            ],
        ]);
    }

    protected function getFields()
    {
        return [
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
            /*[
//                // two interconnected entities
                'label' => __('backpack::permissionmanager.user_role_permission'),
                'field_unique_name' => 'user_role_permission',
                'type' => 'permission_dependency',
                'name' => ['roles', 'permissions'],
                                'primary_query' => function ($query) {
                    if (!backpack_user()->hasRole('super admin')) {
                        $query->where('name', '<>', 'super admin');
                    }
                },
                'subfields' => [
                    'primary' => [
                        'label' => __('backpack::permissionmanager.roles'),
                        'name' => 'roles', // the method that defines the relationship in your Model
                        'entity' => 'roles', // the method that defines the relationship in your Model
                        'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
                        'attribute' => 'display_name', // foreign key attribute that is shown to user
                        'model' => config('permission.models.role'), // foreign key model
                        'pivot' => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns' => 3, //can be 1,2,3,4,6
                    ],
                    'secondary' => [
                        'label' => ucfirst(__('backpack::permissionmanager.permission_singular')),
                        'name' => 'permissions', // the method that defines the relationship in your Model
                        'entity' => 'permissions', // the method that defines the relationship in your Model
                        'entity_primary' => 'roles', // the method that defines the relationship in your Model
                        'attribute' => 'display_name', // foreign key attribute that is shown to user
                        'model' => config('permission.models.permission'), // foreign key model
                        'pivot' => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns' => 3, //can be 1,2,3,4,6
                    ],
                ],
            ],
            [
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

        ];
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
