<?php

namespace Different\DifferentCore\app\Http\Controllers\Cruds;

use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Different\DifferentCore\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
// use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Different\DifferentCore\app\Http\Controllers\Traits\VerifyButton;
use Different\DifferentCore\app\Models\User;
use Different\DifferentCore\app\Models\Role;
use Prologue\Alerts\Facades\Alert;
use Different\DifferentCore\app\Utils\Breadcrumb\BreadcrumbMenuItem;
use Different\DifferentCore\app\Http\Requests\Crud\User\UserStoreRequest;
use Different\DifferentCore\app\Http\Requests\Crud\User\UserUpdateRequest;

class UsersCrudController extends BaseCrudController
{
    use VerifyButton;
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
        $this->crud->setModel(User::class);

        $this->crud->data['delete_modal'] = [
            'title' => __('different-core::users.delete_title'),
            'text' =>  __('different-core::users.delete_text'),
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
        #region Columns
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
        #endregion

        #region Filters
        $this->crud->addFilter([
            'name' => 'name',
            'type' => 'text',
            'label' => __('different-core::users.name'),
        ],
        false,
        function ($value) {
            $this->crud->addClause('where', 'name', 'like', '%' . $value . '%');
        });
        $this->crud->addFilter([
            'name' => 'email',
            'type' => 'text',
            'label' => __('different-core::users.email'),
        ],
        false,
        function ($value) {
            $this->crud->addClause('where', 'email', 'like', '%' . $value . '%');
        });
        $this->crud->addFilter([
            'name' => 'roles',
            'type' => 'select2_multiple',
            'label'=> __('different-core::users.roles'),
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
        #endregion
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
        #region Create & Update Operation
        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => __('different-core::users.name'),
                'type' => 'text',
            ],
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
                'label' => __('different-core::users.user_role_permission'),
                'field_unique_name' => 'user_role_permission',
                'type' => 'roles',
                'view_namespace' => 'different-core::fields',
                'name' => ['roles', 'permissions'],
                'subfields' => [
                    'primary' => [
                        'label' => __('different-core::users.roles'),
                        'name' => 'roles',
                        'entity' => 'roles',
                        'entity_secondary' => 'permissions',
                        'attribute' => 'readable_name',
                        'secondary_attribute' => 'name',
                        'model' => config('permission.models.role'),
                        'pivot' => true,
                        'number_columns' => 4,
                        'order_by' => 'id',
                        'hasGroup' => false,
                    ],
                    'secondary' => [
                        'label' => ucfirst(__('different-core::users.permissions')),
                        'name' => 'permissions',
                        'entity' => 'permissions',
                        'entity_primary' => 'roles',
                        'attribute' => 'name',
                        'secondary_attribute' => 'readable_name',
                        'model' => config('permission.models.permission'),
                        'pivot' => true,
                        'number_columns' => 3,
                        'order_by' => 'group',
                        'hasGroup' => true,
                    ],
                ],
            ],
            /*
            *   ??dv idegen, gondolom azt keresed hogy lehet felt??lteni k??pet vagy f??jlt egy model-hez CRUD-on kereszt??l, h??t tess??k:
            *
            *   'name': mez?? megad??sa k??telez??, fontos, hogy ugyan az legyen mint a realtion. Itt is l??that??, hogy
            *   'profile_image' a mez?? neve.
            *
            *   A `store` ??s `update` met??dust fel??l kell ??rni ??s els?? sorba ezt megh??vni: `$this->handleFileUpload();`
            *   Ez a l??p??s k??s??bb lehet nem kell m??r. Fejleszt??s alatt van.
            *
            *   A model-ben (ezesetben User) le kell kezelni, hogy ha a sort t??rlik akkor a f??jlt is t??r??lj??k ki vele egy??tt.
            *   (Different\DifferentCore\app\Models\User.php -> boot())
            *
            *   Ha szeretn??nk el??n??zetet is (k??p eset??n) akkor a 'has_preview' param??ter ??rt??k??t igazra kell ??ll??tani.
            *
            *   FONTOS: Ahhoz hogy a f??jl felt??lt??s m??k??dj??n a `'upload' => true` k??telez??en meg kell adni. Ez egy Backpack szint?? m??k??d??s.
            *   Adatb??zis szinten figyelni kell hogy ne legyen cascade t??rl??s hiszen akkor a f??jl t??rl??ssel egy??tt az adott elem is t??rl??dik amit
            *   ??ppen szerkeszt??nk.
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
        ]);
        #endregion
    }

    public function store()
    {
        parent::store();
        $this->handlePasswordInput($this->crud->getRequest());
        return $this->traitStore();
    }

    public function update()
    {
        parent::update();
        $this->handlePasswordInput($this->crud->getRequest());
        return $this->traitUpdate();
    }

    #region Nem Backpack met??dusok
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
    #endregion
}
