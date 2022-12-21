<?php

use Different\DifferentCore\app\Http\Controllers\ChangeAccountController;
use Different\DifferentCore\app\Http\Controllers\ChangeLangController;
use Different\DifferentCore\app\Http\Controllers\Cruds\AccountsCrudController;
use Different\DifferentCore\app\Http\Controllers\Cruds\ActivitiesCrudController;
use Different\DifferentCore\app\Http\Controllers\Cruds\PermissionsCrudController;
use Different\DifferentCore\app\Http\Controllers\Cruds\RolesCrudController;
use Different\DifferentCore\app\Http\Controllers\Cruds\SettingsCrudController;
use Different\DifferentCore\app\Http\Controllers\Cruds\UsersCrudController;
use Different\DifferentCore\app\Http\Controllers\FilesController;
use Different\DifferentCore\app\Http\Controllers\MagicLinkController;
use Different\DifferentCore\app\Http\Controllers\Pages\DocumentationPageController;
use Different\DifferentCore\app\Http\Middlewares\DisableDebugbarMiddleware;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => [
        'web',
        DisableDebugbarMiddleware::class,
    ],
], function () {
    Route::get('/file/{file:uuid}', FilesController::class)->name('different-core.file');
    Route::get('/thumbnail/{file:uuid}/{width?}/{height?}', [FilesController::class, 'thumbnail'])->name('different-core.thumbnail');
});

if (config('different-core.config.magic_link_login')) {
    Route::group([
        'prefix' => config('backpack.base.route_prefix', 'admin').'/login',
        'middleware' => [
            'web',
            'guest',
        ],
        'as' => 'magic-link.',
    ], function () {
        Route::get('magic', [MagicLinkController::class, 'getLogin'])->name('get');
        Route::post('magic', [MagicLinkController::class, 'postLogin'])->name('post');
        Route::get('magic/verify/{token}', [MagicLinkController::class, 'verifyLogin'])
            ->middleware(['signed'])
            ->name('verify');
    });
}

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'as' => 'admin.',
], function () {
    Route::crud('user', UsersCrudController::class);
    Route::crud('account', AccountsCrudController::class);
    Route::crud('activity', ActivitiesCrudController::class);
    Route::crud('role', RolesCrudController::class);

    Route::get('change-account/{id}', [ChangeAccountController::class, 'changeAccount'])->name('change-account');
    Route::get('change-lang/{lang}', [ChangeLangController::class, 'changeLang'])->name('change-lang');


    Route::get('/users/{user}/verify', [UsersCrudController::class, 'verifyUser'])->name('verify');
    Route::get('settings', [SettingsCrudController::class, 'index'])->name('settings');
    Route::post('settings', [SettingsCrudController::class, 'save']);

    Route::get('documentation', [DocumentationPageController::class, 'index'])->name('documentation');
});
