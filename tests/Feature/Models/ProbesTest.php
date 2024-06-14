<?php

declare(strict_types=1);

use App\Models\Probes;
use App\Models\User;

it('retrieves probes correctly', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    $response = $this->actingAs($user)->get('/api/probes');
    $response->assertStatus(200);
});

it('creates probe with valid data', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    $probeData = [
        'name' => 'Test Probe',
        'description' => 'This is a test probe',
    ];

    $response = $this->actingAs($user)->post('/api/probes', $probeData);
    $response->assertStatus(201);
    $this->assertDatabaseHas('probes', $probeData);
});

it('retrieves probe correctly by id', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    $probe = Probes::factory()->create();
    $response = $this->actingAs($user)->get('/api/probes/' . $probe->id);
    $response->assertStatus(200);
});

it('updates probe with valid data', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    $probe = Probes::factory()->create();

    $probeData = [
        'name' => 'Updated Probe',
        'description' => 'This is an updated test probe',
    ];

    $response = $this->actingAs($user)->put('/api/probes/' . $probe->id, $probeData);
    $response->assertStatus(200);
    $this->assertDatabaseHas('probes', $probeData);
});

it('deletes probe', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    $probe = Probes::factory()->create();
    $response = $this->actingAs($user)->delete('/api/probes/' . $probe->id);
    $response->assertStatus(200);
    $this->assertDatabaseMissing('probes', ['id' => $probe->id]);
});

it('fails to create probe with invalid data', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    $probeData = [
        'name' => '',
        'description' => '',
        'probe_type_id' => '',
    ];

    $response = $this->actingAs($user)->post('/api/probes', $probeData);
    $response->assertStatus(400);
});

it('fails to update probe with invalid data', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    $probe = Probes::factory()->create();

    $probeData = [
        'name' => '',
        'description' => '',
        'probe_type_id' => '',
    ];

    $response = $this->actingAs($user)->put('/api/probes/' . $probe->id, $probeData);
    $response->assertStatus(400);
});

it('fails to retrieve probe with invalid id', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    $response = $this->actingAs($user)->get('/api/probes/9999');
    $response->assertStatus(404);
});

it('fails to delete probe with invalid id', function (): void {
    $user = User::factory()->withApiToken()->withPersonalTeam()->create();
    $response = $this->actingAs($user)->delete('/api/probes/9999');
    $response->assertStatus(404);
});
