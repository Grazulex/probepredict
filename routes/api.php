<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\MetricTypesController;
use App\Http\Controllers\API\V1\ProbeMetricsController;
use App\Http\Controllers\API\V1\ProbeRulesController;
use App\Http\Controllers\API\V1\ProbesController;
use App\Http\Controllers\API\V1\ProbeTypesController;
use App\Http\Controllers\API\V1\RegisterController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
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
                Route::get('/{probes}', 'show')->middleware(['can:list probes','verify.team']);
                Route::put('/{probes}', 'update')->middleware(['can:create probes','verify.team']);
                Route::delete('/{probes}', 'destroy')->middleware(['can:delete probes','verify.team']);
                Route::post('/', 'store')->middleware(['can:create probes']);
                Route::get('/', 'index')->middleware(['can:list probes']);
            });

        Route::controller(ProbeMetricsController::class)
            ->prefix('metrics')
            ->group(function (): void {
                Route::post('/', 'store')->middleware('can:create metrics');
                Route::delete('/{probeMetrics}', 'destroy')->middleware(['can:delete metrics','verify.team']); //need to add verify.team of probe middleware
            });

        Route::controller(ProbeRulesController::class)
            ->prefix('rules')
            ->group(function (): void {
                Route::post('/', 'store')->middleware('can:create rules');
                Route::put('/{probeRules}', 'update')->middleware(['can:create rules','verify.team']); //need to add verify.team middleware
                Route::delete('/{probeRules}', 'destroy')->middleware(['can:delete rules','verify.team']); //need to add verify.team of probe middleware
            });
    });
});
