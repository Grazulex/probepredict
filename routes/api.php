<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\MetricTypeController;
use App\Http\Controllers\API\V1\ProbeController;
use App\Http\Controllers\API\V1\ProbeMetricController;
use App\Http\Controllers\API\V1\ProbeRuleController;
use App\Http\Controllers\API\V1\ProbeTypeController;
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
        Route::controller(ProbeTypeController::class)
            ->prefix('probe-types')
            ->group(function (): void {
                Route::get('/', 'index')->middleware('can:list types');
                Route::get('/{id}', 'show')->middleware('can:list types');
                Route::put('/{id}', 'update')->middleware('can:administrator types');
                Route::delete('/{id}', 'destroy')->middleware('can:administrator types');
                Route::post('/', 'store')->middleware('can:administrator types');
            });

        Route::controller(MetricTypeController::class)
            ->prefix('metric-types')
            ->group(function (): void {
                Route::get('/', 'index')->middleware('can:list types');
                Route::get('/{id}', 'show')->middleware('can:list types');
                Route::put('/{id}', 'update')->middleware('can:administrator types');
                Route::delete('/{id}', 'destroy')->middleware('can:administrator types');
                Route::post('/', 'store')->middleware('can:administrator types');
            });

        Route::controller(ProbeController::class)
            ->prefix('probes')
            ->group(function (): void {
                Route::get('/{probe}', 'show')->middleware(['can:list probes','verify.team']);
                Route::put('/{probe}', 'update')->middleware(['can:create probes','verify.team']);
                Route::delete('/{probe}', 'destroy')->middleware(['can:delete probes','verify.team']);
                Route::post('/', 'store')->middleware(['can:create probes']);
                Route::get('/', 'index')->middleware(['can:list probes']);
            });

        Route::controller(ProbeMetricController::class)
            ->prefix('metrics')
            ->group(function (): void {
                Route::post('/', 'store')->middleware('can:create metrics');
                Route::delete('/{probeMetric}', 'destroy')->middleware(['can:delete metrics','verify.team']); //need to add verify.team of probe middleware
            });

        Route::controller(ProbeRuleController::class)
            ->prefix('rules')
            ->group(function (): void {
                Route::post('/', 'store')->middleware('can:create rules');
                Route::put('/{probeRule}', 'update')->middleware(['can:create rules','verify.team']); //need to add verify.team middleware
                Route::delete('/{probeRule}', 'destroy')->middleware(['can:delete rules','verify.team']); //need to add verify.team of probe middleware
            });
    });
});
