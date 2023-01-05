<?php

namespace App\Http\Controllers\API\Internal\File;

use App\Http\Controllers\API\ApiController;
use App\Logger;
use App\Models\Events\ModelEventsList;
use App\Models\File\ModelFileList;
use App\Modules\DefaultModules;
use App\Modules\File\FileListModule;
use Cache;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;
use Exception;
use File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Storage;

/**
 * Class ApiControllerInternalFileList
 *
 *
 * @package App\Http\Controllers\API\File
 */

/**
 * @OA\Tag(
 *      name="Internal File List",
 *      description="Внутренний компонент для работы с файлами"
 *  )
 *
 * @OA\Get(
 *     path="/v2/file/list",
 *     tags={"Internal File List"},
 *     summary="Возвращает файлы с пагинаций.",
 *     security={{"api_key": {}}},
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="current_page", type="integer", example=1, description="Текущая страница"),
 *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
 *                  @OA\Property(property="id", type="integer", example=1, description="ID записи"),
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя"),
 *                  @OA\Property(property="size", type="integer", example=252542, description="Размер файлв в кб"),
 *                  @OA\Property(property="type", type="string", example=1, description="Тип файла"),
 *                  @OA\Property(property="md5", type="string", example=1, description="Хеш файла"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было обновлено"),
 *                  @OA\Property(property="deleted_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было удалено"),
 *              )),
 *              @OA\Property(property="first_page_url", type="string", example=1, description="Адрес первой страницы с элементами"),
 *              @OA\Property(property="from", type="integer", example=1),
 *              @OA\Property(property="last_page", type="integer", example=1, description="Номер последней страницы"),
 *              @OA\Property(property="last_page_url", type="string", example=1, description="Последняя страница с элементами"),
 *              @OA\Property(property="next_page_url", type="string", example=1, description="Следующая страница с элементами"),
 *              @OA\Property(property="path", type="string", example=1, description="Адрес с элементами"),
 *              @OA\Property(property="per_page", type="integer", example=1, description="Предыдущая страница"),
 *              @OA\Property(property="prev_page_url", type="string", example=1, description="Предыдущая страница с элементами"),
 *              @OA\Property(property="to", type="integer", example=1),
 *              @OA\Property(property="total", type="integer", example=10, description="Всего страниц с элементами"),
 *          )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error server",
 *     )
 * )
 * @OA\Post(
 *     path="/v2/file/list",
 *     tags={"Internal File List"},
 *     summary="Добавляем файл.",
 *     security={{"api_key": {}}},
 *     @OA\RequestBody(
 *          required=true,
 *     ),
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="boolean", example=1, description="Статус"),
 *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
 *                  @OA\Property(property="id", type="integer", example=1, description="ID записи"),
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя"),
 *                  @OA\Property(property="size", type="integer", example=252542, description="Размер файлв в кб"),
 *                  @OA\Property(property="type", type="string", example=1, description="Тип файла"),
 *                  @OA\Property(property="md5", type="string", example=1, description="Хеш файла"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было обновлено"),
 *                  @OA\Property(property="deleted_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было удалено"),
 *              )),
 *          )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error server",
 *     )
 * )
 * @OA\Get(
 *     path="/v2/file/list/{id}",
 *     tags={"Internal Event List"},
 *     summary="Возвращает файла который хранятся на сервере.",
 *     security={{"api_key": {}}},
 *     @OA\Parameter(
 *         description="ID записи",
 *         in="path",
 *         name="id",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="boolean", example=1, description="Статус"),
 *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
 *                  @OA\Property(property="id", type="integer", example=1, description="ID записи"),
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя"),
 *                  @OA\Property(property="size", type="integer", example=252542, description="Размер файлв в кб"),
 *                  @OA\Property(property="type", type="string", example=1, description="Тип файла"),
 *                  @OA\Property(property="md5", type="string", example=1, description="Хеш файла"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было обновлено"),
 *                  @OA\Property(property="deleted_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было удалено"),
 *              )),
 *          )
 *     ),
 *     security={{"api_key": {}}},
 *     @OA\Response(
 *         response=500,
 *         description="Error server",
 *     )
 * )
 * @OA\Delete(
 *     path="/v2/file/list/{id}",
 *     tags={"Internal File List"},
 *     summary="Удаляем файл",
 *     security={{"api_key": {}}},
 *     @OA\Parameter(
 *         description="ID записи",
 *         in="path",
 *         name="id",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="boolean", example=true, description="Статус"),
 *              @OA\Property(property="message", type="boolean", example="Record success deleted", description="Сообщение"),
 *          )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error server",
 *     )
 * )
 */
class ApiControllerInternalFileList extends ApiController
{
    private $imageExt = ['jpeg', 'gif', 'bmp', 'jpg', 'png'];

    private $audioExt = [];

    private $videoExt = [];

    private $documentExt = [];

    /**
     * ApiControllerInternalFileList constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setModuleResources(DefaultModules::setModel(ModelFileList::query()))
            ->setRoles(['administrator', 'subadministrator']);
    }

    public function show(Request $request, int $id = 0)
    {
        if (!static::checkAccess()) {
            return $this->sendError('Нет доступ на выполнение задачи.', Response::HTTP_FORBIDDEN);
        }

        $this->setCode(Response::HTTP_OK);

        $key_cache = $this->getKeyCache($request);

        if ($this->getCache()) {
            $model = Cache::get("cache_model_{$id}_{$key_cache}");

            if (!is_null($model)) {
                return $this->sendResponse($model->toArray(), $request);
            }
        }

        $array = collect($request->all());

        $array->put('id', $id);

        try {
            $this->getModule()->sql($array);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, []);
        }

        if (is_null($request->get('type_data'))) {
            $model = $this->getModule()->getModel()->first();
        } else {
            if (!in_array($request->get('type_data'), ['array', 'first'])) {
                return $this->sendError('Error param type_data. Only array or first', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $method = ($request->get('type_data') === 'array' || !is_null($request->get('array_model'))) ? 'get' : 'first';

            $model = $this->getModule()->getModel()->$method();
        }

        /** @var Model $model */
        if (is_null($model) || $model->count() === 0) {
            return $this->sendError('Not found model');
        }

        if (!is_null($request->get('type')) && $request->get('type') === 'picture') {
            FileListModule::getFile($model);
            return;
        }

        $this->getModule()->setModel($model);

        if ($this->getCache()) {
            Cache::add("cache_model_{$id}_{$key_cache}", $model, now()->addSeconds(60));
        }

        return $this->sendResponse($this->getModule()->getModel()->toArray(), $request);
    }

    public function store(Request $request)
    {
        $event_id = $request->post('event_id');

        $file = $request->file('file');

        if (is_null($file)) {
            return $this->sendError('Add file', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $ext = $file->getClientOriginalExtension();

        if (collect($this->allExtensions())->search($ext) === false) {
            return $this->sendError('Mime type : ' . $file->getMimeType() . ' (' . $ext . ') is not valid', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $model = new ModelFileList();
        $type = $this->getType($ext);

        if (!File::exists(storage_path() . "/app/")) {
            File::makeDirectory(storage_path() . '/app/', 0777, true);
        }

        if (!File::exists(storage_path() . "/app/files/")) {
            File::makeDirectory(storage_path() . '/app/files/', 0777, true);
        }

        if (!File::exists(storage_path() . "/app/files/$type/")) {
            File::makeDirectory(storage_path() . "/app/files/$type/", 0777, true);
        }

        $md5 = md5_file($file->getRealPath());
        $url = env('API_CRM_URL_PREFIX') . "files/md5/$md5";

        try {

            if($type === 'image'){
                $ext = 'jpg';
                $name = md5_file($file->getRealPath()) . '.' . $ext;
                $path = storage_path("app/files/$type/$name");
                $ImageUpload = Image::make($file);
                $ImageUpload->save($path, 50, $ext);
            } else {
                $name = md5_file($file->getRealPath()) . '.' . $ext;
                $put = Storage::putFileAs("/files/$type", $file, $name);

                if ($put === false) {
                    Logger::error('Error upload file /files/' . $type . ' User: ' . Auth()->user()->id);
                    return $this->sendError('Error add file');
                }
            }

            $fileModel = $model->whereMd5($md5)->first();

            if(is_null($fileModel)){
                $fileModel = $model::firstOrCreate([
                    'md5' => $md5,
                    'type' => $type,
                    'path' => $url,
                    'extension' => $ext,
                    'created_user_id' => Auth()->user()->id
                ]);
            }

            $modelEvent = ModelEventsList::find($event_id);
            $modelEvent->file_id = $fileModel->id;
            $modelEvent->save();

            return $fileModel;
        } catch (Exception $exception) {
            Logger::error('Error upload file ' . $exception->getMessage() . ' User: ' . Auth()->user()->id);
            return $this->sendError('Error add file. Msg: ' . $exception->getMessage());
        }
    }

    /**
     * Get type by extension
     * @param string $ext Specific extension
     * @return string   Type
     */
    private function getType($ext)
    {
        if (in_array($ext, $this->imageExt)) {
            return 'image';
        }

        if (in_array($ext, $this->audioExt)) {
            return 'audio';
        }

        if (in_array($ext, $this->videoExt)) {
            return 'video';
        }

        if (in_array($ext, $this->documentExt)) {
            return 'document';
        }
    }

    /**
     * Get all extensions
     * @return array Extensions of all file types
     */
    private function allExtensions()
    {
        return array_merge($this->imageExt, $this->audioExt, $this->videoExt, $this->documentExt);
    }

}
