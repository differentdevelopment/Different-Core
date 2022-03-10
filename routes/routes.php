<?php

use Illuminate\Support\Facades\Route;

use Different\DifferentCore\app\Http\Controllers\Crud\UsersCrudController;
use Different\DifferentCore\app\Http\Controllers\Crud\ActivitiesCrudController;
use Different\DifferentCore\app\Http\Controllers\Crud\SettingsCrudController;
use Different\DifferentCore\app\Http\Controllers\Crud\RolesCrudController;
use Different\DifferentCore\app\Http\Controllers\Crud\PermissionsCrudController;
use Different\DifferentCore\app\Http\Controllers\Crud\AccountsCrudController;
use Different\DifferentCore\app\Http\Controllers\FilesController;
use Different\DifferentCore\app\Http\Middlewares\DisableDebugbarMiddleware;

Route::group([
    'middleware' => [
        'web',
        DisableDebugbarMiddleware::class
    ],
], function () {
    Route::get('/file/{file:uuid}', FilesController::class)->name('different-core.file');
});

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'as' => 'admin.',
], function () {
    Route::crud('user', UsersCrudController::class);
    Route::crud('account', AccountsCrudController::class);
    Route::crud('activity', ActivitiesCrudController::class);
    Route::crud('permission', PermissionsCrudController::class);
    Route::crud('role', RolesCrudController::class);

    Route::get('/users/{user}/verify', [UsersCrudController::class, 'verifyUser'])->name('verify');
    Route::get('settings', [SettingsCrudController::class, 'index'])->name('settings');
    Route::post('settings', [SettingsCrudController::class, 'save']);
});
