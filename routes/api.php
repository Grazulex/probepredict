<?php

use App\Http\Controllers\API\MetricTypesController;
use App\Http\Controllers\API\ProbesController;
use App\Http\Controllers\API\ProbeTypesController;
use App\Http\Controllers\API\RegisterController;
use Illuminate\Support\Facades\Route;

Route::controller(RegisterController::class)
    ->prefix('auth')
    ->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
    });

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['middleware' => ['role:admin']], function () {
        Route::controller(ProbeTypesController::class)
            ->prefix('probe-types')
            ->group(function () {
                Route::get('/{id}', 'show');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
                Route::post('/', 'store');
            });

        Route::controller(MetricTypesController::class)
            ->prefix('metric-types')
            ->group(function () {
                Route::get('/{id}', 'show');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
                Route::post('/', 'store');
            });
    });

    Route::group(['middleware' => ['role:user']], function () {
        Route::controller(ProbeTypesController::class)
            ->prefix('probe-types')
            ->group(function () {
                Route::get('/', 'index');
            });

        Route::controller(MetricTypesController::class)
            ->prefix('metric-types')
            ->group(function () {
                Route::get('/', 'index');
            });

        Route::controller(ProbesController::class)
            ->prefix('probes')
            ->group(function () {
                Route::get('/{id}', 'show');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
                Route::post('/', 'store');
                Route::get('/', 'index');
            });
    });
});
