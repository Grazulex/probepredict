<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ProbeTypeEnum;
use App\Models\MetricTypes;
use App\Models\ProbeTypes;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user->markEmailAsVerified();
        //Create api token
        $user->createToken('api-token');

        $role_admin = Role::create(['name' => 'administrator']);
        $role_user = Role::create(['name' => 'user']);

        $admin_types = Permission::create(['name' => 'administrator types']);
        $list_types = Permission::create(['name' => 'list types']);

        $create_probes = Permission::create(['name' => 'create probes']);
        $list_probes = Permission::create(['name' => 'list probes']);
        $delete_probes = Permission::create(['name' => 'delete probes']);

        $role_admin->givePermissionTo($admin_types);
        $role_admin->givePermissionTo($list_types);
        $role_admin->givePermissionTo($create_probes);
        $role_admin->givePermissionTo($list_probes);
        $role_admin->givePermissionTo($delete_probes);

        $role_user->givePermissionTo($list_types);
        $role_user->givePermissionTo($list_probes);
        $role_user->givePermissionTo($create_probes);
        $role_user->givePermissionTo($delete_probes);

        $admin_types->assignRole($role_admin);
        $list_types->assignRole($role_admin);
        $create_probes->assignRole($role_admin);
        $list_probes->assignRole($role_admin);
        $delete_probes->assignRole($role_admin);

        $list_types->assignRole($role_user);
        $create_probes->assignRole($role_user);
        $list_probes->assignRole($role_user);
        $delete_probes->assignRole($role_user);

        $user->assignRole(['administrator', 'user']);
        $user->givePermissionTo(['administrator types', 'list types', 'create probes', 'list probes', 'delete probes']);

        ProbeTypes::factory()->create([
            'name' => 'Environmental',
            'description' => 'Environmental probes (temperature, humidity, ...)',
            'enum' => ProbeTypeEnum::ENVIRONMENT->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        ProbeTypes::factory()->create([
            'name' => 'Battery',
            'description' => 'Battery probes (voltage, current, ...)',
            'enum' => ProbeTypeEnum::BATTERY->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        ProbeTypes::factory()->create([
            'name' => 'Car',
            'description' => 'Car probes (voltage, km, ...)',
            'enum' => ProbeTypeEnum::CAR->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        MetricTypes::factory()->create([
            'name' => 'Temperature Celsius',
            'description' => 'Temperature in Celsius',
            'unit' => '°C',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        MetricTypes::factory()->create([
            'name' => 'Temperature Fahrenheit',
            'description' => 'Temperature in Fahrenheit',
            'unit' => '°F',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        MetricTypes::factory()->create([
            'name' => 'Humidity',
            'description' => 'Humidity in percentage',
            'unit' => '%',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}
