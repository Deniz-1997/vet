<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DefaultRoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'administrator', 'guard_name' => 'web']);

        Role::create(['name' => 'administrator', 'guard_name' => 'api']);

        Role::create(['name' => 'subadministrator', 'guard_name' => 'web']);

        Role::create(['name' => 'subadministrator', 'guard_name' => 'api']);

        Role::create(['name' => 'user', 'guard_name' => 'web']);

        Role::create(['name' => 'user', 'guard_name' => 'api']);
    }
}
