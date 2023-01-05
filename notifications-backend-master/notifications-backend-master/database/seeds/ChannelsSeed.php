<?php

use App\Models\Channels\ModelChannels;
use App\Models\Channels\ModelChannelsAdministrators;
use App\Models\Channels\ModelChannelsApi;
use App\Models\Channels\ModelChannelsEvent;
use App\Models\Events\ModelEventsList;
use App\User;
use Illuminate\Database\Seeder;

class ChannelsSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ModelChannels::class, 5)->create()->each(function (ModelChannels $e) {
            $e->administrators()->saveMany(factory(ModelChannelsAdministrators::class, random_int(1, 10))->make([
                'user_id' => User::whereHas('roles', function ($query) {
                    $query->where('name', 'administrator');
                })->get()->random()->id
            ]));

            $e->user()->saveMany(factory(ModelChannelsAdministrators::class, random_int(1, 10))->make([
                'user_id' => User::whereHas('roles', function ($query) {
                    $query->where('name', 'user');
                })->get()->random()->id
            ]));

            $e->apis()->saveMany((factory(ModelChannelsApi::class, 100)->make()));

            $e->events()->saveMany((factory(ModelChannelsEvent::class, random_int(1, 10))->make([
                'event_id' => ModelEventsList::all()->random()->id
            ])));

        });
    }
}
