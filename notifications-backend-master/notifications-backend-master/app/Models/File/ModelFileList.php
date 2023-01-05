<?php

namespace App\Models\File;

/**
 * Class ModelFileList
 * @package App\Models\File
 */
class ModelFileList extends ModelFileSchema
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'path', 'type', 'extension', 'md5', 'created_user_id'];

    /**
     * @param $value
     * @return string
     */
    public function getPathAttribute($value)
    {
        return env('APP_URL').'/'.$value;
    }
}
