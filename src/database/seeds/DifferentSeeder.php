<?php

namespace Different\DifferentCore\Database\Seeds;

use Different\DifferentCore\app\Models\Account;
use Different\DifferentCore\app\Models\Permission;
use Different\DifferentCore\app\Models\Role;
use Different\DifferentCore\app\Models\User;
use Different\DifferentCore\app\Utils\Settings\SettingsManagerController;
use Illuminate\Database\Seeder;

class DifferentSeeder extends Seeder
{
    public function run()
    {
        $user_model = config('backpack.base.user_model_fqn', User::class);
        $account_model = config('backpack.base.account_model_fqn', Account::class);

        //region Felhasználók
        $user = $user_model::query()->firstOrCreate([
            'email' => 'fejlesztes@different.hu',
        ], [
            'name' => 'Different Fejlesztő Kft.',
            'password' => '$2y$10$YoqGMgPRGEOUPg4iFRRPqeyqYX3lsNYeZ4fZPqi/jrPaSEBsTVXUK',
            'remember_token' => null,
            'email_verified_at' => '2020-04-20 04:20:00',
            'created_at' => '2020-04-20 04:20:00',
            'updated_at' => '2020-04-20 04:20:00',
        ]);
        //endregion

        //region Szerepek
        $role_super_admin = Role::query()->firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'web',
            'readable_name' => 'Rendszer Admin',
        ]);
        $role_admin = Role::query()->firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
            'readable_name' => 'Admin',
        ]);
        $user->assignRole($role_super_admin->name);
        //endregion

        //region Jogok
        $permissions = [
            [
                'group' => 'Általános',
                'name' => 'visit-backend',
                'readable_name' => 'Megtekintheti az admin felületet',
            ],
            [
                'group' => 'Felhasználó',
                'name' => 'user-list',
                'readable_name' => 'Felhasználók listázása',
            ],
            [
                'group' => 'Felhasználó',
                'name' => 'user-show',
                'readable_name' => 'Felhasználó adatainak megtekintése',
            ],
            [
                'group' => 'Felhasználó',
                'name' => 'user-create',
                'readable_name' => 'Felhasználó hozzáadása',
            ],
            [
                'group' => 'Felhasználó',
                'name' => 'user-update',
                'readable_name' => 'Felhasználó frissítése',
            ],
            [
                'group' => 'Felhasználó',
                'name' => 'user-delete',
                'readable_name' => 'Felhasználó törlése',
            ],
            [
                'group' => 'Bejegyzés',
                'name' => 'post-list',
                'readable_name' => 'Bejegyzésk listázása',
            ],
            [
                'group' => 'Bejegyzés',
                'name' => 'post-show',
                'readable_name' => 'Bejegyzés megtekintése',
            ],
            [
                'group' => 'Bejegyzés',
                'name' => 'post-create',
                'readable_name' => 'Bejegyzés hozzáadása',
            ],
            [
                'group' => 'Bejegyzés',
                'name' => 'post-update',
                'readable_name' => 'Bejegyzés frissítése',
            ],
            [
                'group' => 'Bejegyzés',
                'name' => 'post-delete',
                'readable_name' => 'Bejegyzés törlése',
            ],
            [
                'group' => 'Általános',
                'name' => 'activity-list',
                'readable_name' => 'Aktivitások listázása',
            ],
            [
                'group' => 'Általános',
                'name' => 'setting-manage',
                'readable_name' => 'Beállítások megtekintése és szerkesztése',
            ],
            [
                'group' => 'Általános',
                'name' => 'role-manage',
                'readable_name' => 'Szerepek megtekintése és szerkesztése',
            ],
            [
                'group' => 'Általános',
                'name' => 'select-all-accounts',
                'readable_name' => 'Minden accounthoz hozzáfér'
            ]
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission['name'],
                'guard_name' => 'web',
                'group' => $permission['group'],
                'readable_name' => $permission['readable_name'],
            ]);
            $role_admin->givePermissionTo($permission['name']);
        }
        //endregion

        //region Fiókok
        $account = $account_model::query()->firstOrCreate([
            'name' => 'Different Fejlesztő Kft.',
        ]);
        $account->users()->syncWithoutDetaching($user->id);
        //endregion

        //region Beállítások
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
        // SettingsManagerController::delete('company_name');
        //endregion
    }
}
