<?php

namespace App\Modules\File;

use App\Modules\Module;
use App\Modules\TraitModule;
use Storage;

/**
 * Class FileListModule
 * @package App\Modules\File
 */
class FileListModule extends Module
{
    use TraitModule;


    public static function getFile($model)
    {
        if ($model->type === 'image') {
            $mimetype = Storage::mimeType('/files/image/' . $model->md5 . '.' . $model->extension);
            header("Content-type: {$mimetype}");
            echo Storage::get('/files/image/' . $model->md5 . '.' . $model->extension);
        } else {
            $mimetype = Storage::mimeType('/files/' . $model->type . '/' . $model->md5 . '.' . $model->extension);
            header("Content-type: {$mimetype}");
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"$model->md5.$model->extension\"");
            echo readfile(storage_path('app/files/' . $model->type . '/' . $model->md5 . '.' . $model->extension));
        }
    }
}
