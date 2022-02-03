<?php

use Illuminate\Support\Facades\Route;

use Different\DifferentCore\app\Http\Controllers\UsersCrudController;
use Different\DifferentCore\app\Http\Controllers\ActivitiesCrudController;
use Different\DifferentCore\app\Http\Controllers\SettingsCrudController;
use Different\DifferentCore\app\Http\Controllers\FilesController;
use Different\DifferentCore\app\Http\Controllers\RolesCrudController;
use Different\DifferentCore\app\Http\Middlewares\DisableDebugbarMiddleware;
use Backpack\PermissionManager\app\Http\Controllers\PermissionCrudController;

Route::group([
    'middleware' => [
        DisableDebugbarMiddleware::class
    ],
], function () {
    Route::get('/file/{file}', FilesController::class)->name('file');
});

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'as' => 'admin',
], function () {
    Route::crud('user', class_exists('App\Http\Controllers\Admin\UsersCrudController')
        ? \App\Http\Controllers\Admin\UsersCrudController::class
        : UsersCrudController::class
    );
    /*Route::crud('/roles', class_exists('App\Http\Controllers\Admin\RolesCrudController')
        ? \App\Http\Controllers\Admin\RolesCrudController::class
        : RolesCrudController::class
    );*/

    Route::crud('activity', ActivitiesCrudController::class);
    Route::crud('permission', PermissionCrudController::class);
    Route::crud('role', RolesCrudController::class);
    
    Route::get('settings', [SettingsCrudController::class, 'index']);
    Route::post('settings', [SettingsCrudController::class, 'save']);

    /*Route::get('/users/{user}/verify', [UsersCrudController::class, 'verifyUser'])->name('.verify');
    Route::get('/user', [UsersCrudController::class, 'abortUserGrid']);
    Route::post('/change-account', [AuthController::class, 'changeAccount'])->name('.change_account');
    
    // PARTNERS
    Route::group([
        'prefix' => 'partners',
        'as' => '.partners',
    ], function () {
        Route::get('ajax_partner_list', [PartnersCrudController::class, 'ajaxList'])->name('.ajax-partner-list');
        Route::crud('', PartnersCrudController::class);
    });*/
});
