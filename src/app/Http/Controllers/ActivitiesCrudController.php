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
    use ShowOperation;
    use ListOperation;
    use FetchOperation;

    public function setup()
    {
        crud_permission($this->crud, 'activity-list');
        $this->crud->setRoute(backpack_url('activity'));
        $this->crud->setEntityNameStrings(__('different-core::activities.activity'), __('different-core::activities.activities'));
        $this->crud->setModel(Activity::class);
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

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);
        $this->crud->setColumns([
            [
                'name' => 'created_at',
                'label' => __('different-core::activities.created_at'),
                'type' => 'datetime',
            ],
            [
                'name' => 'log_name',
                'label' => __('different-core::activities.log_name'),
                'type' => 'text',
            ],
            [
                'name' => 'description',
                'label' => __('different-core::activities.description'),
                'type' => 'text',
            ],
            [
                'name' => 'subject',
                'label' => __('different-core::activities.subject'),
                'type' => 'closure',
                'function' => function($entry) {
                    if ($entry->subject === null) {
                        return '';
                    }
                    return '<a href="' . backpack_url($entry->subject->getTable() . '/' . $entry->subject->id . '/show') . '">' . $entry->subject->name . '</a>';
                }
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
                'name' => 'properties',
                'label' => __('different-core::activities.properties'),
                'type' => 'closure',
                'function' => function($entry) {
                    if ($entry->properties === []) {
                        return '';
                    }

                    return '<code class="prettyprint">' . $entry->properties . '</code>';
                }
            ],
        ]);
    }
}
