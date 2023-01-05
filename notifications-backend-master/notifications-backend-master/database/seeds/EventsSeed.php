<?php

use App\Models\Events\ModelEventsList;
use App\Models\Events\ModelEventsTemplates;
use App\Models\Templates\ModelTemplatesList;
use Illuminate\Database\Seeder;

class EventsSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        factory(ModelEventsList::class, 4)->create()->each(function (ModelEventsList $e) {
            $e->templates()->save(
                factory(ModelEventsTemplates::class)->make([
                    'template_id' => ModelTemplatesList::all()->random()->id
                ])
            );
        });
    }
}
