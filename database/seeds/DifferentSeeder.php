<?php

namespace Different\DifferentCore\Database\Seeds;

use Different\DifferentCore\app\Models\User;
use Different\DifferentCore\app\Models\Account;
use Different\DifferentCore\app\Utils\Settings\SettingsManagerController;

//use Backpack\Settings\app\Models\Setting;
//use Different\Dwfw\app\Models\Account;
//use Different\Dwfw\app\Models\TimeZone;
use Illuminate\Database\Seeder;
//use Spatie\Permission\Models\Permission;
//use Spatie\Permission\Models\Role;

class DifferentSeeder extends Seeder
{
    /*protected $setting = [
        [
            'key' => 'NOTICE_TIME',
            'name' => 'NOTICE_TIME',
            'description' => 'Idő amíg a NOTICE log törtlődik(nap)',
            'value' => '7',      
            'Field' => '{"name":"value","label":"Value","type":"number"}',
            'active' => 1,
        ],
        [
            'key' => 'WARNING_TIME',
            'name' => 'WARNING_TIME',
            'description' => 'Idő amíg a WARNING log törtlődik(nap)',
            'value' => '30',      
            'Field' => '{"name":"value","label":"Value","type":"number"}',
            'active' => 1,
        ],
        [
            'key' => 'ERROR_TIME',
            'name' => 'ERROR_TIME',
            'description' => 'Idő amíg a ERROR log törtlődik(nap)',
            'value' => '0',      
            'Field' => '{"name":"value","label":"Value","type":"number"}',
            'active' => 1,
        ],
        [
            'key' => 'NOTICE_CATEGORY',
            'name' => 'NOTICE_CATEGORY',
            'description' => 'Eventek amik a NOTICE kategóriába tartoznak',
            'value' => '',      
            'Field' => '{"name":"value","label":"value","type":"repeatable","fields":{"type":"event"}}',
            'active' => 1,
        ],
        [
            'key' => 'WARNING_CATEGORY',
            'name' => 'WARNING_CATEGORY',
            'description' => 'Eventek amik a WARNING kategóriába tartoznak',
            'value' => '',      
            'Field' => '{"name":"value","label":"value","type":"repeatable","fields":{"type":"event"}}',
            'active' => 1,
        ],
        [
            'key' => 'ERROR_CATEGORY',
            'name' => 'ERROR_CATEGORY',
            'description' => 'Eventek amik az ERROR kategóriába tartoznak',
            'value' => '',      
            'Field' => '{"name":"value","label":"value","type":"repeatable","fields":{"type":"event"}}',
            'active' => 1,
        ],
    ];
    const PERMISSIONS = [
        'login backend',
        'manage users',
        'manage bans',
        'view logs',
        'manage settings',
        'manage roles',
        'manage permissions',
    ];

    const ACCOUNT_RELATED_PERMISSIONS = [
        'change account'
    ];*/

    public function run()
    {
        // USERS
        $user = User::query()->firstOrCreate([
            'email' => 'fejlesztes@different.hu',
        ], [
            'name' => 'Fejlesztés',
            'email_verified_at' => '2020-04-20 04:20:00',
            'password' => '$2y$10$YoqGMgPRGEOUPg4iFRRPqeyqYX3lsNYeZ4fZPqi/jrPaSEBsTVXUK',
            'remember_token' => null,
            'phone' => '+36204377776',
            'created_at' => '2020-04-20 04:20:00',
            'updated_at' => '2020-04-20 04:20:00',
        ]);

        // BASE ROLES
        /*$role_super_admin = Role::query()->firstOrCreate([
            'name' => 'super admin',
            'guard_name' => 'web',
        ],[]);

        $role_admin = Role::query()->firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ], []);

        foreach(self::PERMISSIONS as $permission)
        {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ], []);
            $role_admin->givePermissionTo($permission);
        }

        if(config('dwfw.has_accounts')){
            foreach(self::ACCOUNT_RELATED_PERMISSIONS as $permission)
            {
                Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => 'web',
                ], []);
                $role_admin->givePermissionTo($permission);
            }
        }


        // add admin role to base user
        $user->assignRole($role_super_admin->name);
        */

        // TIMEZONES
        /*$timestamp = time();
        foreach (timezone_identifiers_list() as $key => $zone) {
            date_default_timezone_set($zone);
            TimeZone::query()->firstOrCreate([
                'name' => $zone,
                'diff' => date('P', $timestamp),
            ], []);
        }

        // update users with default timezone
        User::query()->whereNull('timezone_id')->update([
            'timezone_id' => TimeZone::DEFAULT_TIMEZONE_CODE
        ]);*/

        // PARTNERS
        /*$partner = Partner::query()->firstOrCreate([
            'name' => 'Different Fejlesztő Kft.',
        ], [
            'contact_name' => 'Vezető Viktor',
            'contact_phone' => '+362013455467',
            'contact_email' => 'php@different.hu',
        ]);*/

        $account = Account::query()->firstOrCreate([
            'name' => 'Different Fejlesztő Kft.',
        ]);
        $account->users()->syncWithoutDetaching($user->id);
        
        
        // ***********************
        // Settings
        // ***********************

        SettingsManagerController::create([
            [
                'name' => 'company_name',
                'type' => 'text',
                'tab' => 'Rendszer',
                'label' => 'Cégnév',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'value' => 'Different Fejlesztő Kft.',
            ],
        ]);
    }
}
