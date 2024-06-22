<?php

declare(strict_types=1);

use App\Models\MetricType;
use App\Models\Probe;
use App\Models\ProbeMetric;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

it('stores probe metric with valid data', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    Permission::create(['name' => 'create metrics']);
    $user->givePermissionTo('create metrics');

    $probe = Probe::factory()->create(
        [
            'team_id' => $user->currentTeam->id,
        ],
    );

    $metric_type = MetricType::factory()->create();
    $probeMetricData = [
        'probe_id' => $probe->id,
        'metric_type_id' => $metric_type->id,
        'value' => 100,
    ];

    $response = $this->actingAs($user)->post('/api/v1/metrics', $probeMetricData);
    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('probe_metrics', $probeMetricData);
});

it('fails to store probe metric with invalid data', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    Permission::create(['name' => 'create metrics']);
    $user->givePermissionTo('create metrics');

    $probeMetricData = [
        'probe_id' => '',
        'metric_type_id' => '',
        'value' => '',
    ];

    $response = $this->actingAs($user)->post('/api/v1/metrics', $probeMetricData);
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('deletes probe metric', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    Permission::create(['name' => 'delete metrics']);
    $user->givePermissionTo('delete metrics');

    $probe = Probe::factory()->create(
        [
            'team_id' => $user->currentTeam->id,
        ],
    );

    $metric_type = MetricType::factory()->create();
    $probeMetric = ProbeMetric::factory()->create(
        [
            'probe_id' => $probe->id,
            'metric_type_id' => $metric_type->id,
            'value' => 100,
        ],
    );
    $response = $this->actingAs($user)->delete('/api/v1/metrics/' . $probeMetric->id);
    $response->assertStatus(Response::HTTP_NO_CONTENT);
    $this->assertDatabaseMissing('probe_metrics', ['id' => $probeMetric->id]);
});

it('fails to delete probe metric with invalid id', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    Permission::create(['name' => 'delete metrics']);
    $user->givePermissionTo('delete metrics');

    $response = $this->actingAs($user)->delete('/api/probe-metrics/9999');
    $response->assertStatus(Response::HTTP_NOT_FOUND);
});
