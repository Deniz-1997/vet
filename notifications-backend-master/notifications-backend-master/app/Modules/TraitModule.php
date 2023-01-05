<?php


namespace App\Modules;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Trait TraitModule
 * @package App\Modules
 */
trait TraitModule
{

    /**
     * @var Model|Builder|SoftDeletes
     */
    public static $model;

    /**
     * @return Builder|Model
     */
    public static function getModel()
    {
        return static::$model;
    }

    /**
     * Set model
     *
     * @param $model
     * @return |Module
     */
    public static function setModel($model)
    {
        static::$model = $model;

        return new static();
    }

    /**
     * @param array $array
     * @return TraitModule
     */
    public static function setRules(array $array)
    {
        static::$rules = $array;
        return new static();
    }

    /**
     * @return array
     */
    public static function getRules(): array
    {
        return static::$rules;
    }
}
