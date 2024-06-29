<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ProbeTypeEnum;
use App\Models\MetricType;
use App\Models\ProbeType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->withPersonalTeam()->create();

        $user = User::factory()->withPersonalTeam()->create([
            'name' => 'administrator',
            'email' => 'jms@grazulex.be',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user->markEmailAsVerified();
        //Create api token
        $user->createToken('ProbePredict');

        $role_admin = Role::create(['name' => 'administrator']);
        $role_user = Role::create(['name' => 'user']);

        $admin_types = Permission::create(['name' => 'administrator types']);
        $list_types = Permission::create(['name' => 'list types']);

        $create_probes = Permission::create(['name' => 'create probes']);
        $list_probes = Permission::create(['name' => 'list probes']);
        $delete_probes = Permission::create(['name' => 'delete probes']);

        $create_metrics = Permission::create(['name' => 'create metrics']);
        $delete_metrics = Permission::create(['name' => 'delete metrics']);
        $list_metrics = Permission::create(['name' => 'list metrics']);

        $create_rules = Permission::create(['name' => 'create rules']);
        $delete_rules = Permission::create(['name' => 'delete rules']);
        $list_rules = Permission::create(['name' => 'list rules']);

        $role_admin->givePermissionTo($admin_types);
        $role_admin->givePermissionTo($list_types);
        $role_admin->givePermissionTo($create_probes);
        $role_admin->givePermissionTo($list_probes);
        $role_admin->givePermissionTo($delete_probes);
        $role_admin->givePermissionTo($create_metrics);
        $role_admin->givePermissionTo($delete_metrics);
        $role_admin->givePermissionTo($list_metrics);
        $role_admin->givePermissionTo($create_rules);
        $role_admin->givePermissionTo($delete_rules);
        $role_admin->givePermissionTo($list_rules);

        $role_user->givePermissionTo($list_types);
        $role_user->givePermissionTo($list_probes);
        $role_user->givePermissionTo($create_probes);
        $role_user->givePermissionTo($delete_probes);
        $role_user->givePermissionTo($create_metrics);
        $role_user->givePermissionTo($delete_metrics);
        $role_user->givePermissionTo($list_metrics);
        $role_user->givePermissionTo($create_rules);
        $role_user->givePermissionTo($delete_rules);
        $role_user->givePermissionTo($list_rules);

        $admin_types->assignRole($role_admin);
        $list_types->assignRole($role_admin);
        $create_probes->assignRole($role_admin);
        $list_probes->assignRole($role_admin);
        $delete_probes->assignRole($role_admin);
        $create_metrics->assignRole($role_admin);
        $delete_metrics->assignRole($role_admin);
        $list_metrics->assignRole($role_admin);
        $create_rules->assignRole($role_admin);
        $delete_rules->assignRole($role_admin);
        $list_rules->assignRole($role_admin);

        $list_types->assignRole($role_user);
        $create_probes->assignRole($role_user);
        $list_probes->assignRole($role_user);
        $delete_probes->assignRole($role_user);
        $create_metrics->assignRole($role_user);
        $delete_metrics->assignRole($role_user);
        $list_metrics->assignRole($role_user);
        $create_rules->assignRole($role_user);
        $delete_rules->assignRole($role_user);
        $list_rules->assignRole($role_user);

        $user->assignRole(['administrator', 'user']);
        $user->givePermissionTo(['administrator types', 'list types', 'create probes', 'list probes', 'delete probes', 'create metrics', 'delete metrics', 'list metrics', 'create rules', 'delete rules', 'list rules']);

        ProbeType::factory()->create([
            'name' => 'Environmental',
            'description' => 'Environmental probes (temperature, humidity, ...)',
            'enum' => ProbeTypeEnum::ENVIRONMENT->value,
        ]);
        ProbeType::factory()->create([
            'name' => 'Battery',
            'description' => 'Battery probes (voltage, current, ...)',
            'enum' => ProbeTypeEnum::BATTERY->value,
        ]);
        ProbeType::factory()->create([
            'name' => 'Car',
            'description' => 'Car probes (voltage, km, ...)',
            'enum' => ProbeTypeEnum::CAR->value,
        ]);
        MetricType::factory()->create([
            'name' => 'Temperature Celsius',
            'description' => 'Temperature in Celsius',
            'unit' => '°C',
        ]);
        MetricType::factory()->create([
            'name' => 'Temperature Fahrenheit',
            'description' => 'Temperature in Fahrenheit',
            'unit' => '°F',
        ]);
        MetricType::factory()->create([
            'name' => 'Humidity',
            'description' => 'Humidity in percentage',
            'unit' => '%',
        ]);

    }
}
