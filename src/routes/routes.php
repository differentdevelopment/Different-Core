<?php

use Different\DifferentCore\app\Http\Controllers\ChangeAccountController;
use Different\DifferentCore\app\Http\Controllers\ChangeLangController;
use Different\DifferentCore\app\Http\Controllers\Cruds\AccountsCrudController;
use Different\DifferentCore\app\Http\Controllers\Cruds\ActivitiesCrudController;
use Different\DifferentCore\app\Http\Controllers\Cruds\FilesCrudController;
use Different\DifferentCore\app\Http\Controllers\Cruds\RolesCrudController;
use Different\DifferentCore\app\Http\Controllers\Cruds\SettingsCrudController;
use Different\DifferentCore\app\Http\Controllers\Cruds\UsersCrudController;
use Different\DifferentCore\app\Http\Controllers\FilesController;
use Different\DifferentCore\app\Http\Middlewares\DisableDebugbarMiddleware;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => [
        'web',
        DisableDebugbarMiddleware::class,
    ],
], function () {
    Route::get('change-lang/{lang}', [ChangeLangController::class, 'changeLang'])->name('change-lang');
    Route::get('/file/{file:uuid}', FilesController::class)->name('different-core.file');
    Route::get('/file/{file:uuid}/download', [FilesController::class, 'download'])->name('different-core.file-download');
    Route::get('/thumbnail/{file:uuid}/{width?}/{height?}', [FilesController::class, 'thumbnail'])->name('different-core.thumbnail');
});


Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'as' => 'admin.',
], function () {
    Route::crud('user', UsersCrudController::class);
    Route::get('/users/{user}/verify', [UsersCrudController::class, 'verifyUser'])->name('verify');
    
    Route::crud('account', AccountsCrudController::class);
    Route::crud('activity', ActivitiesCrudController::class);
    Route::crud('role', RolesCrudController::class);
    Route::crud('filemanager', FilesCrudController::class);

    Route::get('change-account/{id}', [ChangeAccountController::class, 'changeAccount'])->name('change-account');

    Route::get('settings', [SettingsCrudController::class, 'index'])->name('settings');
    Route::post('settings', [SettingsCrudController::class, 'save']);
});

