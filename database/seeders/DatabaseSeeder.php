<?php

namespace Database\Seeders;

use App\Models\MetricTypes;
use App\Models\ProbeTypes;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
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

        $role_admin = Role::create(['name' => 'admin']);
        $role_user = Role::create(['name' => 'user']);
        $admin_types = Permission::create(['name' => 'admin types']);
        $admin_probes = Permission::create(['name' => 'admin probes']);

        $role_admin->givePermissionTo($admin_types);
        $role_admin->givePermissionTo($admin_probes);
        $role_user->givePermissionTo($admin_probes);

        $admin_types->assignRole($role_admin);
        $admin_probes->assignRole($role_admin);
        $admin_types->assignRole($role_user);

        $user->assignRole(['admin', 'user']);

        ProbeTypes::factory()->create([
            'name' => 'Environmental',
            'description' => 'Environmental probes',
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
