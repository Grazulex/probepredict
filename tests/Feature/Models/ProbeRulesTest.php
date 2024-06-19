<?php

declare(strict_types=1);

use App\Models\MetricTypes;
use App\Models\Probes;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

test('create probe rules correctly', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    Permission::create(['name' => 'create rules']);
    $user->givePermissionTo('create rules');

    $probe = Probes::factory()->create(
        [
            'team_id' => $user->currentTeam->id,
        ],
    );

    $metric_type = MetricTypes::factory()->create();

    $probeData = [
        'probe_id' => $probe->id,
        'metric_type_id' => $metric_type->id,
        'operator' => '<',
        'condition' => '20',
    ];

    $response = $this->actingAs($user)->post('/api/v1/rules', $probeData);
    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('probe_rules', $probeData);
});

test('update rules', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    Permission::create(['name' => 'create rules']);
    Permission::create(['name' => 'update rules']);
    $user->givePermissionTo(['update rules', 'create rules']);

    $probe = Probes::factory()->create(
        [
            'team_id' => $user->currentTeam->id,
        ],
    );

    $metric_type = MetricTypes::factory()->create();

    $probeData = [
        'probe_id' => $probe->id,
        'metric_type_id' => $metric_type->id,
        'operator' => '<',
        'condition' => '20',
    ];

    $response = $this->actingAs($user)->post('/api/v1/rules', $probeData);
    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('probe_rules', $probeData);

    $probeData['condition'] = '30';
    $response = $this->actingAs($user)->put('/api/v1/rules/' . $probe->id, $probeData);
    $response->assertStatus(Response::HTTP_OK);
    $this->assertDatabaseHas('probe_rules', $probeData);
});

test('delete rules', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    Permission::create(['name' => 'create rules']);
    Permission::create(['name' => 'delete rules']);
    $user->givePermissionTo(['delete rules', 'create rules']);

    $probe = Probes::factory()->create(
        [
            'team_id' => $user->currentTeam->id,
        ],
    );

    $metric_type = MetricTypes::factory()->create();

    $probeData = [
        'probe_id' => $probe->id,
        'metric_type_id' => $metric_type->id,
        'operator' => '<',
        'condition' => '20',
    ];

    $response = $this->actingAs($user)->post('/api/v1/rules', $probeData);
    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('probe_rules', $probeData);

    $response = $this->actingAs($user)->delete('/api/v1/rules/' . $probe->id);
    $response->assertStatus(Response::HTTP_NO_CONTENT);
    $this->assertDatabaseMissing('probe_rules', $probeData);
});
