<?php

declare(strict_types=1);

use App\Models\Probes;
use App\Models\ProbeTypes;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

it('retrieves probes correctly', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    Permission::create(['name' => 'list probes']);
    $user->givePermissionTo('list probes');
    $response = $this->actingAs($user)->get('/api/probes');

    $response->assertStatus(Response::HTTP_OK);
});

it('creates probe with valid data', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    Permission::create(['name' => 'create probes']);
    Permission::create(['name' => 'administrator types']);
    $user->givePermissionTo(['create probes', 'administrator types']);
    //create probe type
    $probeType = ProbeTypes::factory()->create();

    $probeData = [
        'name' => 'Test Probe',
        'description' => 'This is a test probe',
        'probe_type_id' => $probeType->id,
    ];

    $response = $this->actingAs($user)->post('/api/probes', $probeData);
    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('probes', $probeData);
});

it('retrieves probe correctly by id', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    Permission::create(['name' => 'list probes']);
    $user->givePermissionTo('list probes');
    $probe = Probes::factory()->create(
        [
            'team_id' => $user->currentTeam->id,
        ],
    );
    $response = $this->actingAs($user)->get('/api/probes/' . $probe->id);
    $response->assertStatus(Response::HTTP_OK);
});

it('updates probe with valid data', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    Permission::create(['name' => 'create probes']);
    Permission::create(['name' => 'administrator types']);
    $user->givePermissionTo(['create probes', 'administrator types']);
    $probe = Probes::factory()->create(
        [
            'team_id' => $user->currentTeam->id,
        ],
    );

    $probeData = [
        'name' => 'Updated Probe',
        'description' => 'This is an updated test probe',
        'probe_type_id' => $probe->probe_type_id,
    ];

    $response = $this->actingAs($user)->put('/api/probes/' . $probe->id, $probeData);
    $response->assertStatus(Response::HTTP_OK);
    $this->assertDatabaseHas('probes', $probeData);
});

it('deletes probe', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    Permission::create(['name' => 'delete probes']);
    $user->givePermissionTo('delete probes');
    $probe = Probes::factory()->create(
        [
            'team_id' => $user->currentTeam->id,
        ],
    );
    $response = $this->actingAs($user)->delete('/api/probes/' . $probe->id);
    $response->assertStatus(Response::HTTP_NO_CONTENT);
    $this->assertDatabaseMissing('probes', ['id' => $probe->id]);
});

it('fails to create probe with invalid data', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    Permission::create(['name' => 'create probes']);
    Permission::create(['name' => 'administrator types']);
    $user->givePermissionTo(['create probes', 'administrator types']);
    $probeData = [
        'name' => '',
        'description' => '',
        'probe_type_id' => '',
    ];

    $response = $this->actingAs($user)->post('/api/probes', $probeData);
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('fails to update probe with invalid data', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    Permission::create(['name' => 'create probes']);
    $user->givePermissionTo('create probes');
    $probe = Probes::factory()->create(
        [
            'team_id' => $user->currentTeam->id,
        ],
    );

    $probeData = [
        'name' => '',
        'description' => '',
        'probe_type_id' => '',
    ];

    $response = $this->actingAs($user)->put('/api/probes/' . $probe->id, $probeData);
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('fails to create probe without permission', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    $probeData = [
        'name' => 'Test Probe',
        'description' => 'This is a test probe',
        'probe_type_id' => 1,
    ];

    $response = $this->actingAs($user)->post('/api/probes', $probeData);
    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('fails to update probe without permission', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    $probe = Probes::factory()->create(
        [
            'team_id' => $user->currentTeam->id,
        ],
    );

    $probeData = [
        'name' => 'Updated Probe',
        'description' => 'This is an updated test probe',
        'probe_type_id' => 1,
    ];

    $response = $this->actingAs($user)->put('/api/probes/' . $probe->id, $probeData);
    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('fails to delete probe without permission', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    $probe = Probes::factory()->create(
        [
            'team_id' => $user->currentTeam->id,
        ],
    );
    $response = $this->actingAs($user)->delete('/api/probes/' . $probe->id);
    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('fails to retrieve probes without permission', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    $response = $this->actingAs($user)->get('/api/probes');
    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('fails to retrieve probe if not in same team', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    Permission::create(['name' => 'list probes']);
    $user->givePermissionTo('list probes');
    $probe = Probes::factory()->create();
    $response = $this->actingAs($user)->get('/api/probes/' . $probe->id);
    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('fails to retrieve probe with invalid id', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    $response = $this->actingAs($user)->get('/api/probes/9999');
    $response->assertStatus(Response::HTTP_NOT_FOUND);
});

it('fails to delete probe with invalid id', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    $response = $this->actingAs($user)->delete('/api/probes/9999');
    $response->assertStatus(Response::HTTP_NOT_FOUND);
});
