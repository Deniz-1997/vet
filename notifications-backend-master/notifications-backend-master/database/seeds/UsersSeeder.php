<?php

use App\Models\Dictionary\ModelDictionaryGroupUsers;
use App\Models\Dictionary\ModelDictionaryStructures;
use App\Models\Dictionary\ModelDictionarySubstructures;
use App\Models\User\ModelInfoUsers;
use App\Models\User\ModelUserDevices;
use App\Models\User\ModelUserGroups;
use App\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 4)->create()->each(function (User $u) {
            $group = ModelDictionaryGroupUsers::all()->random();

            $u->groups()->save(
                factory(ModelUserGroups::class)->make(['group_id' => $group->id])
            );

            $devices = ['android', 'ios'];

            $u->device()->save(
                factory(ModelUserDevices::class)->make(['device' => $devices[rand(0, (count($devices) - 1))]])
            );

            $roles = ['administrator', 'user'];

            $u->assignRole($roles[random_int(0, 1)]);
        });
    }
}
