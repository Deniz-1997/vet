<?php

namespace App\Modules;

use App\Exceptions\ValidationException;
use App\Traits\TraitValidator;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Schema;

/**
 * Class Module
 * @package App\Modules
 * @method static Model getModel()
 * @method static TraitModule setModel($model)
 */
class Module
{
    use TraitValidator;

    /**
     * @param array $array
     * @return mixed
     * @throws ValidationException
     * @throws Exception
     */
    public static function create(array $array)
    {
        static::validation($array);

        $array = collect(array_merge($array, [
            'created_user_id' => Auth()->user()->id,
        ]));

        if(is_null($array->get('user_id'))){
            $array->push('user_id', Auth()->user()->id);
        }

        $model = static::getModel()->create($array->toArray());

        static::setModel($model);

        return new static();
    }

    /**
     * @param array $array
     */
    public static function update(array $array): void
    {
        $model = static::getModel();

        $columns = self::getColumns($model);

        foreach ($array as $key => $value) {
            if (array_search($key, $columns) !== false && $model->$key !== $value) {
                $model->update([
                    $key => $value
                ]);
            }
        }
    }


    /**
     * Return name module
     *
     * @return string|string[]|null
     */
    public static function getNameModule(): string
    {
        return str_replace('\\', '_', get_class(new static()));
    }


    /**
     * Init a methods for the model
     *
     * @param Collection $collection
     * @throws Exception
     */
    public static function sql(Collection $collection): void
    {
        if (is_numeric($collection->get('id')) && !is_null($collection->get('id'))) {
            static::getModel()->whereId($collection->get('id'));
        }

        static::with($collection);

        static::latest($collection);

        static::where($collection);

        static::whereDate($collection);

        static::order($collection);

        static::select($collection);

        static::withTrashed($collection);

        static::join($collection);

        static::limit($collection);

        static::setModel(static::getModel());
    }

    /**
     * Loading Multiple Relationships
     *
     * @link https://laravel.com/docs/master/eloquent-relationships#eager-loading
     * @param Collection $collection
     * @return void
     */
    public static function with(Collection $collection): void
    {
        $with = $collection->get('with');

        $model = static::getModel();

        if (!is_null($with) && static::isJson($with)) {
            $with = json_decode($with, 'array');

            if (count($with) > 0) {
                $model->with($with);
            }
        }
    }

    /**
     * Order results by date or other column
     *
     * @link https://laravel.com/docs/5.8/queries#ordering-grouping-limit-and-offset
     * @param Collection $collection
     * @return void
     */
    public static function latest(Collection $collection): void
    {
        $latest = $collection->get('latest');

        $model = static::getModel();

        if (!empty($latest) && !is_null($latest)) {
            $model->latest($latest);
        }
    }

    /**
     * Add where clauses to the model
     *
     * @link https://laravel.com/docs/5.8/queries#where-clauses
     * @param Collection $collection
     * @return void
     */
    public static function where(Collection $collection): void
    {
        $data = $collection->get('where');

        $model = static::getModel();

        $columns = self::getColumns($model);

        if (!empty($data) && !is_null($data) && static::isJson($data)) {

            $where = json_decode($data, true);

            if (count($where) > 0) {

                foreach ($where as $item) {

                    if (count($item) < 3) {
                        continue;
                    }

                    if ($item[1] === 'in') {
                        if(is_array($item[2]) && count($item[2]) > 0){
                            $model->whereIn($item[0], $item[2]);
                        }
                    } elseif ($item[1] === 'like') {
                        if (array_search($item[0], $columns) !== false) {
                            $val = preg_replace("/perc/", "%", $item[2]);

                            if(!empty($val)){
                                $model->where($item[0], $item[1], $val);
                            }
                        }
                    } else {
                        # if exists column
                        if (array_search($item[0], $columns) !== false) {
                            if(!empty($item[2])){
                                $model->where($item[0], $item[1], $item[2]);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Add where clauses(only date) to the model
     *
     * @link https://laravel.com/docs/5.8/queries#where-clauses
     * @param Collection $collection
     * @return void
     */
    public static function whereDate(Collection $collection): void
    {
        $data = $collection->get('whereDate');

        if (!empty($data) && !is_null($data) && static::isJson($data)) {

            $whereDate = json_decode($data, true);

            if (count($whereDate) === 2) {
                static::getModel()->whereDate($whereDate[0], $whereDate[1]);
            }
        }
    }

    /**
     * The method allows you to sort the result of the query by a given column
     *
     * @link https://laravel.com/docs/queries#ordering-grouping-limit-and-offset
     * @param Collection $collection
     * @return void
     */
    public static function order(Collection $collection): void
    {
        $data = $collection->get('order');

        if (!empty($data) && !is_null($data) && static::isJson($data)) {
            $order = json_decode($data, true);

            if (count($order) === 2) {
                static::getModel()->orderBy($order[0], $order[1]);
            }
        }
    }

    /**
     * Using the select method, you can specify a custom select clause for the query
     *
     * @link https://laravel.com/docs/queries#selects
     * @param Collection $collection
     * @return void
     */
    public static function select(Collection $collection): void
    {
        $data = $collection->get('select');

        $model = static::getModel();

        $columns = self::getColumns($model);

        if (!empty($data) && !is_null($data) && static::isJson($data)) {
            $select = json_decode($data, true);

            if (count($select) > 0) {
                $array = collect();


                if (is_array($select)) {
                    foreach ($select as $item) {
                        if (array_search($item, $columns) !== false) {
                            $array->push($item);
                        }
                    }
                } elseif (is_string($select)) {
                    if (array_search($select, $columns) !== false) {
                        $array->push($select);
                    }
                }

                if ($array->count() > 0) {
                    static::getModel()->select($select);
                }
            }
        }
    }

    /**
     * You may force soft deleted models to appear in a result set using the withTrashed method on the query
     *
     * @link https://laravel.com/docs/eloquent#querying-soft-deleted-models
     * @param Collection $collection
     * @return void
     */
    public static function withTrashed(Collection $collection): void
    {
        $data = $collection->get('withTrashed');

        if ((int)$data === 1 && !is_null($data)) {
            static::getModel()->withTrashed();
        }
    }

    /**
     * Join to multiple tables in a single query
     *
     * @link https://laravel.com/docs/queries#joins
     * @param Collection $collection
     * @throws Exception
     * @return void
     */
    public static function join(Collection $collection): void
    {
        $data = $collection->get('join');

        if (!empty($data) && !is_null($data) && static::isJson($data)) {

            $join = json_decode($data, true);

            if (count($join) > 0) {

                if (!isset($join['join']) && !static::isJson($join['join'])) {
                    throw new Exception('Incorrect array join.');
                }

                if (!isset($join['select']) && !static::isJson($join['join'])) {
                    throw new Exception('Incorrect array select.');
                }

                $joins = json_decode($join['join'], true);

                $select = json_decode($join['select'], true);

                foreach ($joins as $j) {

                    if (!is_array($j)) {
                        throw new Exception('Incorrect params. Not found array');
                    }

                    if (count($j) !== 4) {
                        throw new Exception('Incorrect params. Array < 4');
                    }

                    $method = $j[0];

                    static::getModel()->$method($j[1], $j[2], '=', $j[3]);
                }

                if (count($select) > 0) {
                    static::getModel()->select($select);
                }

                if (isset($join['where']) && static::isJson($join['where'])) {
                    $where = json_decode($join['where'], true);

                    if (count($where) > 0) {
                        foreach ($where as $item) {
                            $w = (isset($item[3]) && $item[3] === "or") ? "orWhere" : "where";

                            static::getModel()->$w($item[0], $item[1], $item[2]);
                        }
                    }
                }
            }
        }
    }

    /**
     * To limit the number of results returned from the query
     *
     * @link https://laravel.com/docs/queries#ordering-grouping-limit-and-offset
     * @param Collection $collection
     * @throws Exception
     * @return void
     */
    public static function limit(Collection $collection): void
    {
        $data = $collection->get('limit');

        if (!empty($data) && is_numeric($data) && !is_null($data)) {

            $limit = (int)$data;

            if ($limit <= 0) {
                throw new Exception('Limit less than or equal to zero.');
            }

            static::getModel()->limit($limit);
        }
    }

    /**
     * Check if the string is JSON
     *
     * @param $string
     * @return bool
     */
    public static function isJson($string): bool
    {
        json_decode($string, true);
        return (json_last_error() === JSON_ERROR_NONE);
    }

    /**
     * @param Model|Builder $model
     * @return array
     */
    public static function getColumns($model): array
    {
        $schema = (!is_null($model->getModel()->getConnectionName())) ?
            Schema::connection($model->getModel()->getConnectionName()) : null;

        return is_null($schema) ? Schema::getColumnListing($model->getModel()->getTable()) :
            $schema->getColumnListing($model->getModel()->getTable());
    }
}
