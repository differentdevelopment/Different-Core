<?php

namespace Different\DifferentCore\app\Http\Controllers\Cruds;

use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Different\DifferentCore\app\Http\Controllers\Operations\DeleteOperation;
use Different\DifferentCore\app\Http\Requests\Crud\User\UserStoreRequest;
use Different\DifferentCore\app\Http\Requests\Crud\User\UserUpdateRequest;
use Different\DifferentCore\app\Models\Role;
use Different\DifferentCore\app\Models\User;
use Different\DifferentCore\app\Services\LangService;
use Different\DifferentCore\app\Utils\Breadcrumb\BreadcrumbMenuItem;
use Illuminate\Support\Facades\Cache;
use Prologue\Alerts\Facades\Alert;

class UsersCrudController extends BaseCrudController
{
    use ListOperation;
    use ShowOperation;
    use CreateOperation {
        store as traitStore;
    }
    use UpdateOperation {
        update as traitUpdate;
    }
    use DeleteOperation;

    public function setup()
    {
        crud_permissions($this->crud, 'user');
        $this->crud->setRoute(backpack_url('user'));
        $this->crud->setEntityNameStrings(__('different-core::users.user'), __('different-core::users.users'));
        $this->crud->setModel(config('backpack.base.user_model_fqn', User::class));

        $this->crud->data['delete_modal'] = [
            'title' => __('different-core::users.delete_title'),
            'text' => __('different-core::users.delete_text'),
        ];

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
            'name' => 'avatar_with_name',
            'label' => __('different-core::users.name'),
            'type' => 'view',
            'view' => 'different-core::columns.avatar',
        ]);
        /*$this->crud->addColumn([
            'name' => 'name',
            'label' => __('different-core::users.name'),
            'type' => 'text',
        ]);*/
        $this->crud->addColumn([
            'name' => 'email',
            'label' => __('different-core::users.email'),
            'type' => 'email',
        ]);
        $this->crud->addColumn([
            'label' => __('different-core::users.roles'),
            'type' => 'select_multiple',
            'name' => 'roles',
            'entity' => 'roles',
            'attribute' => 'readable_name',
            'model' => config('permission.models.role'),
        ]);
        //endregion

        //region Filters
        $this->crud->addFilter(
            [
                'name' => 'name',
                'type' => 'text',
                'label' => __('different-core::users.name'),
            ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'name', 'like', '%' . $value . '%');
            }
        );
        $this->crud->addFilter(
            [
                'name' => 'email',
                'type' => 'text',
                'label' => __('different-core::users.email'),
            ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'email', 'like', '%' . $value . '%');
            }
        );
        $this->crud->addFilter([
            'name' => 'roles',
            'type' => 'select2_multiple',
            'label' => __('different-core::users.roles'),
        ], function () {
            return Role::all()->keyBy('id')->pluck('readable_name', 'id')->toArray();
        }, function ($values) {
            $this->crud->query = $this->crud->query->whereHas('roles', function ($q) use ($values) {
                foreach (json_decode($values) as $key => $value) {
                    if ($key == 0) {
                        $q->where('roles.id', $value);
                    } else {
                        $q->orWhere('roles.id', $value);
                    }
                }
            });
        });
        //endregion
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(UserStoreRequest::class);
        $this->addFields();
    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(UserUpdateRequest::class);
        $this->addFields();
    }

    protected function addFields()
    {
        //region Create & Update Operation
        $this->crud->addFields(
            array_merge( //Hogy feltételesen tehessem be a Lang selectort, ha több lang van, csak akkor jelenjen meg
                [
                    [
                        'name' => 'name',
                        'label' => __('different-core::users.name'),
                        'type' => 'text',
                        'wrapper' => [
                            'class' => 'form-group col-md-10'
                        ]
                    ],
                ],
                (count(LangService::getAvailableLangShortcodesInArray()) > 1) ?
                    [[
                        'name' => 'lang',
                        'label' => __('different-core::users.lang'),
                        'type' => 'select2_from_array',
                        'allows_null' => true,
                        'options' => LangService::getAvailableLangShortcodesInArray(),
                        'wrapper' => [
                            'class' => 'form-group col-md-2'
                        ]
                    ]] : [[
                        'name' => 'lang_hidden',
                        'type' => 'hidden',
                        'value' => null,
                    ]],
                [
                    [
                        'name' => 'email',
                        'label' => __('different-core::users.email'),
                        'type' => 'email',
                    ],
                    [
                        'name' => 'password',
                        'label' => __('different-core::users.password'),
                        'type' => 'password',
                    ],
                    [
                        'name' => 'password_confirmation',
                        'label' => __('different-core::users.password_confirmation'),
                        'type' => 'password',
                    ],
                    [
                        'name' => 'accounts',
                        'type' => 'select2_multiple',
                        'label' => __('different-core::accounts.accounts'),
                        'pivot' => true,
                        'options' => (function ($query) {
                            return $query->orderBy('name', 'asc')->get();
                        }),
                        'events' => [
                            'updating' => function ($entry) {
                                Cache::forget('selectable_accounts_for_user_' . backpack_user()->id);
                            },
                        ],
                    ],
                    [
                        // two interconnected entities
                        'label'             => trans('different-core::permissions.user_role_permission'),
                        'field_unique_name' => 'user_role_permission',
                        'type'              => 'checklist_dependency',
                        'name'              => 'roles,permissions',
                        'subfields'         => [
                            'primary' => [
                                'label'            => trans('different-core::roles.role'),
                                'name'             => 'roles', // the method that defines the relationship in your Model
                                'entity'           => 'roles', // the method that defines the relationship in your Model
                                'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
                                'attribute'        => 'name', // foreign key attribute that is shown to user
                                'model'            => config('permission.models.role'), // foreign key model
                            ],
                            'secondary' => [
                                'label'            => mb_ucfirst(trans('different-core::permissions.permission')),
                                'name'             => 'permissions', // the method that defines the relationship in your Model
                                'entity'           => 'permissions', // the method that defines the relationship in your Model
                                'entity_primary'   => 'roles', // the method that defines the relationship in your Model
                                'attribute'        => 'name', // foreign key attribute that is shown to user
                                'model'            => config('permission.models.permission'), // foreign key model,
                            ],
                        ],
                        'events' => [
                            'updating' => function ($entry) {
                                Cache::forget('selectable_accounts_for_user_' . backpack_user()->id);
                            },
                        ],
                    ],
                    /*
                *   Üdv idegen, gondolom azt keresed hogy lehet feltölteni képet vagy fájlt egy model-hez CRUD-on keresztül, hát tessék:
                *
                *   'name': mező megadása kötelező, fontos, hogy ugyan az legyen mint a realtion. Itt is látható, hogy
                *   'profile_image' a mező neve.
                *
                *   A `store` és `update` metódust felül kell írni és első sorba ezt meghívni: `$this->handleFileUpload();`
                *   Ez a lépés később lehet nem kell már. Fejlesztés alatt van.
                *
                *   A model-ben (ezesetben User) le kell kezelni, hogy ha a sort törlik akkor a fájlt is töröljük ki vele együtt.
                *   (Different\DifferentCore\app\Models\User.php -> boot())
                *
                *   Ha szeretnénk előnézetet is (kép esetén) akkor a 'has_preview' paraméter értékét igazra kell állítani.
                *
                *   FONTOS: Ahhoz hogy a fájl feltöltés működjön a `'upload' => true` kötelezően meg kell adni. Ez egy Backpack szintű működés.
                *   Adatbázis szinten figyelni kell hogy ne legyen cascade törlés hiszen akkor a fájl törléssel együtt az adott elem is törlődik amit
                *   éppen szerkesztünk.
                */
                    [
                        'name' => 'profile_image',
                        'label' => __('different-core::users.profile_image'),
                        'view_namespace' => 'different-core::fields',
                        'type' => 'file',
                        'has_preview' => true,
                        'upload' => true,
                        'wrapper' => [
                            'class' => 'form-group col-12',
                        ],
                    ],
                ]
            )
        );
        //endregion
    }

    public function store()
    {
        $this->handlePasswordInput($this->crud->getRequest());
        return parent::store();
    }

    public function update()
    {
        $this->handlePasswordInput($this->crud->getRequest());
        return parent::update();
    }

    //region Nem Backpack metódusok
    protected function handlePasswordInput($request)
    {
        $crud_request = $this->crud->getRequest();
        if ($request->input('password')) {
            $hashed_password = bcrypt($request->input('password'));
            $crud_request->request->set('password', $hashed_password);
            $crud_request->request->set('password_confirmation', $hashed_password);
        } else {
            $crud_request->request->remove('password');
            $crud_request->request->remove('password_confirmation');
        }
        $this->crud->setRequest($crud_request);
    }

    public function verifyUser(User $user)
    {
        $user->verify();
        Alert::success(__('different-core::users.verified'))->flash();

        return redirect(backpack_url('users'));
    }
    //endregion
}
