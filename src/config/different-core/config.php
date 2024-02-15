<?php

return [
    // "Account" választó megjelenítése (globális)
    'show_account_selector' => env('CORE_SHOW_ACCOUNT_SELECTOR', true),

    // Ha projekt szintent szeretnéd az error kezelést akkor ezt állítsd false-ra
    'project_uses_core_error_handling' => env('CORE_PROJECT_USES_CORE_ERROR_HANDLING', true),

    'permissionmanager' => [
        'models' => [
            'user' => config('backpack.base.user_model_fqn', Different\DifferentCore\app\Models\User::class),
            'permission' => Different\DifferentCore\app\Models\Permission::class,
            'role' => Different\DifferentCore\app\Models\Role::class,
        ],
        
        'allow_permission_create' => true,
        'allow_permission_update' => true,
        'allow_permission_delete' => true,
        'allow_role_create' => true,
        'allow_role_update' => true,
        'allow_role_delete' => true,
    ]
];
