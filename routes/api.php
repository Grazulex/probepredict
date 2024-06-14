<?php

declare(strict_types=1);

use App\Http\Controllers\API\MetricTypesController;
use App\Http\Controllers\API\ProbeMetricsController;
use App\Http\Controllers\API\ProbeRulesController;
use App\Http\Controllers\API\ProbesController;
use App\Http\Controllers\API\ProbeTypesController;
use App\Http\Controllers\API\RegisterController;
use Illuminate\Support\Facades\Route;

Route::controller(RegisterController::class)
    ->prefix('auth')
    ->group(function (): void {
        Route::post('register', 'register');
        Route::post('login', 'login');
    });

Route::group(['middleware' => ['auth:sanctum']], function (): void {
    Route::group(['middleware' => ['role:admin']], function (): void {
        Route::controller(ProbeTypesController::class)
            ->prefix('probe-types')
            ->group(function (): void {
                Route::get('/{id}', 'show');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
                Route::post('/', 'store');
            });

        Route::controller(MetricTypesController::class)
            ->prefix('metric-types')
            ->group(function (): void {
                Route::get('/{id}', 'show');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
                Route::post('/', 'store');
            });
    });

    Route::group(['middleware' => ['role:user']], function (): void {
        Route::controller(ProbeTypesController::class)
            ->prefix('probe-types')
            ->group(function (): void {
                Route::get('/', 'index');
            });

        Route::controller(MetricTypesController::class)
            ->prefix('metric-types')
            ->group(function (): void {
                Route::get('/', 'index');
            });

        Route::controller(ProbesController::class)
            ->prefix('probes')
            ->group(function (): void {
                Route::get('/{id}', 'show');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
                Route::post('/', 'store');
                Route::get('/', 'index');
            });

        Route::controller(ProbeMetricsController::class)
            ->prefix('metrics')
            ->group(function (): void {
                Route::get('/{id}', 'index');
                Route::post('/', 'store');
                Route::delete('/{id}', 'destroy');
            });

        Route::controller(ProbeRulesController::class)
            ->prefix('rules')
            ->group(function (): void {
                Route::post('/', 'store');
                Route::get('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
            });

    });
});
