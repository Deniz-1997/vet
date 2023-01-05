<?php

use App\Models\Dictionary\ModelDictionaryGroupUsers;
use App\Models\Dictionary\ModelDictionaryHistoriesType;
use Illuminate\Database\Seeder;

class DictionarySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ModelDictionaryGroupUsers::class, 5)->create();

        factory(ModelDictionaryHistoriesType::class, 5)->create();
    }
}
