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
    Route::controller(ProbeTypesController::class)
        ->prefix('probe-types')
        ->group(function (): void {
            Route::get('/', 'index')->middleware('can:list types');
            Route::get('/{id}', 'show')->middleware('can:list types');
            Route::put('/{id}', 'update')->middleware('can:administrator types');
            Route::delete('/{id}', 'destroy')->middleware('can:administrator types');
            Route::post('/', 'store')->middleware('can:administrator types');
        });

    Route::controller(MetricTypesController::class)
        ->prefix('metric-types')
        ->group(function (): void {
            Route::get('/', 'index')->middleware('can:list types');
            Route::get('/{id}', 'show')->middleware('can:list types');
            Route::put('/{id}', 'update')->middleware('can:administrator types');
            Route::delete('/{id}', 'destroy')->middleware('can:administrator types');
            Route::post('/', 'store')->middleware('can:administrator types');
        });

    Route::controller(ProbesController::class)
        ->prefix('probes')
        ->group(function (): void {
            Route::get('/{probe}', 'show')->middleware(['can:list probes','verify.team']);
            Route::put('/{probe}', 'update')->middleware(['can:create probes','verify.team']);
            Route::delete('/{probe}', 'destroy')->middleware(['can:delete probes','verify.team']);
            Route::post('/', 'store')->middleware(['can:create probes']);
            Route::get('/', 'index')->middleware(['can:list probes']);
        });

    Route::controller(ProbeMetricsController::class)
        ->prefix('metrics')
        ->group(function (): void {
            Route::post('/', 'store')->middleware('can:create metrics');
            Route::delete('/{probeMetrics}', 'destroy')->middleware('can:delete metrics');
        });

    Route::controller(ProbeRulesController::class)
        ->prefix('rules')
        ->group(function (): void {
            Route::post('/', 'store')->middleware('can:create rules');
            Route::put('/{probeRules}', 'update')->middleware('can:create rules');
            Route::delete('/{probeRules}', 'destroy')->middleware('can:delete rules');
        });
});
