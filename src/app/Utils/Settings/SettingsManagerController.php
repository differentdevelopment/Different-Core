<?php

namespace Different\DifferentCore\app\Utils\Settings;

use Different\DifferentCore\app\Models\Setting;
use Illuminate\Support\Arr;

class SettingsManagerController
{
    /**
     * Retrive the settings from DB.
     *
     * @return void
     */
    public function getDatabaseSettings() {
        return Setting::all();
    }

    /**
     * Deletes unused settings from database. This uses the seeder as the only source of truth
     *
     * @return void
     */
    public static function cleanUpDatabaseSettings($seededSettings) {
        $settings_in_db = Setting::query()->get()->toArray();
        if(!empty($settings_in_db) && !empty($seededSettings)) {
            $settings_in_db = Arr::pluck($settings_in_db, 'name');
            if(!empty($diff = array_diff($settings_in_db, $seededSettings))) {
                foreach ($diff as $settingToDelete) {
                    if (in_array($settingToDelete, $settings_in_db)) {
                        Setting::where('name', $settingToDelete)->first()->delete();
                    }
                }
            }
        }
    }

    /**
     * Returns the setting value.
     *
     * @param string $setting 
     * @return mixed
     */
    public function get($setting)
    {
        $name = is_string($setting) ? $setting : (is_array($setting) ? $setting['name'] : abort(500, 'Could not parse setting.'));
        $setting = Setting::query()->where('name', $name)->first();
        if (isset($setting) && !empty($setting)) {
            return $setting?->value ?? "";
        }

        return "";
    }

    public static function settingExists($setting) {
        $name = is_string($setting) ? $setting : (is_array($setting) ? $setting['name'] : abort(500, 'Could not parse setting.'));
        return Setting::query()->where('name', $name)->count() > 0;
    }

    public static function create($settings) {
        foreach ($settings as $setting) {
            $dbSetting = null;
            $setting['type'] ?? abort(500, 'Setting need a type.');
            $setting['name'] ?? abort(500, 'Setting need a name.');
            $setting['tab'] = $setting['tab'] ?? null;
            $setting['group'] = $setting['group'] ?? null;
            $setting['value'] = $setting['value'] ?? null;
            $setting['label'] = $setting['label'] ?? $setting['name'];

            $settingOptions = Arr::except($setting, ['type', 'name', 'label', 'tab', 'group', 'value', 'id']);

            $dbSetting = Setting::where('name', $setting['name'])->first();
            
            if (!isset($dbSetting) && is_null($dbSetting)) {
                $dbSetting = Setting::create([
                    'name' => $setting['name'],
                    'type' => $setting['type'],
                    'label' => $setting['label'],
                    'tab' => $setting['tab'] ?? null,
                    'group' => $setting['group'] ?? null,
                    'value' => $setting['value'] ?? null,
                ]);
            }

            $dbSetting->options = $settingOptions;
            $dbSetting->save();
        }
    }

    public static function delete($settingName) {
        $dbSetting = Setting::where('name', $settingName)->first();

        if (isset($dbSetting) && !is_null($dbSetting)) {
            $dbSetting->delete();
        }
    }

    public static function getFieldValidations($settings) {
        $validations = array();
        foreach(Setting::query()->whereIn('name', array_keys($settings))->get() as $setting) {
            $validations[$setting['name']] = $setting['options']['validation'] ?? null;
        }
        return array_filter($validations);
    }

    public static function saveSettingsValues($settings) {
        $settingsInDb = Setting::whereIn('name', array_keys($settings))->get();
        foreach($settings as $settingName => $settingValue) {
            $setting = $settingsInDb->where('name',$settingName)->first();
            if (!is_null($setting)) {
                /*switch ($setting->type) { TODO: Fixme
                    case 'image': {
                        $settingValue = $this->saveImageToDisk($settingValue, $settingName);
                    }
                }*/
                $setting->update(['value' => $settingValue]);
            }

        }
        return true;
    }

    public static function getFieldsForEditor() {
        $settings = Setting::query()->get();
        foreach ($settings as &$setting) {
            foreach ($setting->options as $key => $option) {
                $setting->{$key} = $option;
            }
            unset($setting->options);
        }
        return $settings->keyBy('name')->toArray();
    }

    public function update(array $setting) {
        return Setting::where('name', $setting['name'])->update(Arr::except($setting, ['name']));
    }

    public function saveImageToDisk($image, $settingName)
    {
        // TODO: Fixme
        /*$disk = config('bpsettings.image_save_disk');
        $prefix = config('bpsettings.image_save_disk');

        $setting = Setting::where('name', $settingName)->first();

        if ($image === null) {
            // delete the image from disk
            if(Storage::disk($disk)->has($setting->value))
            {
                Storage::disk($disk)->delete($setting->value);
            }

            // set null in the database column
            return null;
        }

        // if a base64 was sent, store it in the db
        if (Str::startsWith($image, 'data:image'))
        {
            // 0. Make the image
            $imageCreated = \Image::make($image);

            // 1. Generate a filename.
            $filename = md5($image.time()).'.jpg';
            // 2. Store the image on disk.
            if(Storage::disk($disk)->has($setting->value))
            {
                Storage::disk($disk)->delete($setting->value);
            }

            Storage::disk($disk)->put($prefix.$filename, $imageCreated->stream());
            // 3. Save the path to the database

            return $prefix.$filename;
        }

        return $setting->value;*/
    }
}
