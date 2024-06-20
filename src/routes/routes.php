<?php

use Different\DifferentCore\app\Http\Controllers\ChangeAccountController;
use Different\DifferentCore\app\Http\Controllers\ChangeLangController;
use Different\DifferentCore\app\Http\Controllers\Cruds\AccountsCrudController;
use Different\DifferentCore\app\Http\Controllers\Cruds\ActivitiesCrudController;
use Different\DifferentCore\app\Http\Controllers\Cruds\FilesCrudController;
use Different\DifferentCore\app\Http\Controllers\Cruds\RolesCrudController;
use Different\DifferentCore\app\Http\Controllers\Cruds\SettingsCrudController;
use Different\DifferentCore\app\Http\Controllers\Cruds\UsersCrudController;
use Different\DifferentCore\app\Http\Controllers\Cruds\PostsCrudController;
use Different\DifferentCore\app\Http\Controllers\FilesController;
use Different\DifferentCore\app\Http\Controllers\MagicLinkController;
use Different\DifferentCore\app\Http\Middlewares\DisableDebugbarMiddleware;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => [
        'web',
        DisableDebugbarMiddleware::class,
    ],
], function () {
    Route::get('change-lang/{lang}', [ChangeLangController::class, 'changeLang'])->name('change-lang');

    if(!config('different-core.config.unique_file_uuid_for_every_session_or_token')){
        Route::get('/file/{file:uuid}', FilesController::class)->name('different-core.file');
        Route::get('/file/{file:uuid}/download', [FilesController::class, 'download'])->name('different-core.file-download');
        Route::get('/thumbnail/{file:uuid}/{width?}/{height?}', [FilesController::class, 'thumbnail'])->name('different-core.thumbnail');
    }else{
        Route::get('/file/{uuid}', [FilesController::class, 'getFileComplexUuid'])->name('different-core.file');
        Route::get('/file/{uuid}/download', [FilesController::class, 'downloadComplexUuid'])->name('different-core.file-download');
        Route::get('/thumbnail/{uuid}/{width?}/{height?}', [FilesController::class, 'thumbnailComplexUuid'])->name('different-core.thumbnail');
    }
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
    Route::crud('post', PostsCrudController::class);
    Route::crud('user', UsersCrudController::class);
    Route::crud('account', AccountsCrudController::class);
    Route::crud('activity', ActivitiesCrudController::class);
    Route::crud('role', RolesCrudController::class);
    Route::crud('filemanager', FilesCrudController::class);


    Route::get('change-account/{id}', [ChangeAccountController::class, 'changeAccount'])->name('change-account');

    Route::get('/users/{user}/verify', [UsersCrudController::class, 'verifyUser'])->name('verify');
    Route::get('settings', [SettingsCrudController::class, 'index'])->name('settings');
    Route::post('settings', [SettingsCrudController::class, 'save']);
});