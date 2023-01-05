<?php

use App\Models\Dictionary\ModelDictionaryGroupUsers;
use App\Models\Templates\ModelTemplatesGroupUser;
use App\Models\Templates\ModelTemplatesList;
use Illuminate\Database\Seeder;

class TemplateSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ModelTemplatesList::class, 4)->create()->each(function (ModelTemplatesList $e) {
            $e->templateUserGroup()->saveMany(
                factory(ModelTemplatesGroupUser::class, random_int(1, 5))->make([
                    'group_id' => ModelDictionaryGroupUsers::all()->random()->id
                ])
            );
        });
    }
}
