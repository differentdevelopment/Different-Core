<?php

namespace Different\DifferentCore\app\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Different\DifferentCore\app\Models\Setting;
use Different\DifferentCore\app\Utils\Settings\SettingsManagerController;

class SettingsCrudController extends CrudController
{
    public function setup() {
        $this->crud->setRoute(backpack_url('settings'));
        $this->crud->setModel(Setting::class);
        $this->crud->setEntityNameStrings(trans('different-core::settings.setting'), trans('different-core::settings.settings')); // TODO: Fixme
    }

    public function index() {
        $fields = SettingsManagerController::getFieldsForEditor();

        foreach($fields as $field) {
            $this->crud->addField($field);
        }
        
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin') => backpack_url('dashboard'),
            trans('different-core::settings.settings') => false,
        ];
        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);

        return view('different-core::settings.settings', $this->data);
    }

    public function save(Request $request) {
        $settings = $request->except(['http_referrer', '_token']);
        $validationRules = SettingsManagerController::getFieldValidations($settings);
        
        Validator::make($settings, $validationRules)->validate();

        if(SettingsManagerController::saveSettingsValues($settings)) {
            return response()->json('success');
        }

        return response()->json('saving error');
    }
}
