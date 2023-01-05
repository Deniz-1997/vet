<?php


namespace App\Http\Controllers;


use App\Models\File\ModelFileList;
use App\Modules\File\FileListModule;
use Exception;
use Storage;

class FilesController extends Controller
{
    /**
     * @param string $md5
     * @throws Exception
     */
    public function getByMD5(string $md5)
    {
        $model = ModelFileList::where('md5', '=', $md5)->first();

        if (is_null($model)) {
            throw new Exception('Not found file');
        }

        try {
            FileListModule::getFile($model);
        } catch (Exception $exception) {
            echo 'Error open file';
        }
    }

}
