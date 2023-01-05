<?php

namespace App\Modules\Dictionary;

use App\Models\Dictionary\ModelDictionaryOrganizations;
use App\Modules\Module;
use App\Modules\TraitModule;
use Illuminate\Support\Collection;

/**
 * Class DictionaryOrganizationsModule
 * @package App\Modules\Dictionary
 */
class DictionaryOrganizationsModule extends Module
{
    use TraitModule;

    /**
     * @var array
     */
    public static $rules = [
        'name' => ['required', 'string', 'max:255', 'unique:dictionary.model_dictionary_organizations'],
        'parent_id' => ['integer', 'exists:dictionary.model_dictionary_organizations,id']
    ];

    /**
     * @param int $organization_id
     * @return mixed
     */
    public static function getMainOrganizationId(int $organization_id)
    {
        $model = ModelDictionaryOrganizations::find($organization_id);

        if (is_null($model)) {
            return null;
        }

        if (is_null($model->parent_id)) {
            return $model->id;
        } else {
            $parent_not_null = true;
            $id = $model->parent_id;

            do {
                $model = ModelDictionaryOrganizations::find($id);

                if (is_null($model->parent_id)) {
                    $parent_not_null = false;
                    $id = $model->id;
                } else {
                    $id = $model->parent_id;
                }
            } while ($parent_not_null);

            return $id;
        }
    }

    /**
     * @param int $id
     * @return Collection
     */
    public static function getHierarchyById(int $id)
    {
        $model = ModelDictionaryOrganizations::find($id);

        if (is_null($model->parent_id)) {
            return collect([$model->id]);
        }

        $collect = collect();

        $parent_not_null = true;

        $id = $model->parent_id;

        do {
            $collect->push($id);

            $model = ModelDictionaryOrganizations::find($id);

            if (is_null($model->parent_id)) {
                $parent_not_null = false;
                $id = $model->id;
                $collect->push($id);
            } else {
                $id = $model->parent_id;
            }
        } while ($parent_not_null);

        return $collect->unique();
    }

    /**
     * Проверяем организацию по иерархии
     *
     * @param int $organization_id
     * @return bool
     */
    public static function checkMainOrganizations(int $organization_id)
    {
        return $organization_id === static::getMainOrganizationId($organization_id);
    }
}
