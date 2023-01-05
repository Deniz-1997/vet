<?php
namespace App\Controller;

use App\Entity\Appointment\AppointmentFormTemplate;
use App\Entity\Reference\TemplateReferenceValue;
use App\Model\Env;
use App\Packages\DTO\Request\CreatePrintFormRequest;
use App\Entity\Appointment\Appointment;
use App\Entity\File;
use App\Entity\Owner;
use App\Entity\Owner\FileOwner;
use App\Entity\Pet\Pet;
use App\Entity\Pet\PetToOwner;
use App\Entity\PrintForm;
use App\Entity\Settings;
use App\Entity\UploadedFile;
use App\Packages\PrintEngine\PrintEngine;
use App\Repository\Appointment\AppointmentRepository;
use App\Repository\OwnerRepository;
use App\Repository\Pet\PetRepository;
use App\Repository\PrintFormsRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\TemplateProcessor;
use Ramsey\Uuid\Uuid;
use OpenApi\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;
use App\Service\DeserializeService;
use App\Service\PrintFormHistoryService;

/**
 * Class PrintFormsController
 * @Route("/api/print", name="asdasdasd")
 * @Resource(
 *     description="Main desc",
 *     tags={"PrintForms"},
 *     summariesMap={
 *          "list": "Получить список печатных форм",
 *          "get": "Получить печатную форму",
 *          "post": "Создать печатную форму",
 *          "delete": "Удалить печатную форму",
 *          "patch": "Обновить печатную форму"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список печатных форм",
 *          "get": "Возвращает печатную форму по идентификатору",
 *          "post": "Создает новый печатную форму",
 *          "delete": "Удаляет существующий печатную форму",
 *          "patch": "Обновляет существующий печатную форму"
 *     }
 * )
 */
class PrintFormsController extends AbstractController
{
    public const ENTITY_CLASS = PrintForm::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /** @var PrintFormsRepository */
    private PrintFormsRepository $printFormsRepository;

    /** @var PetRepository */
    private PetRepository $petRepository;

    /** @var OwnerRepository */
    private OwnerRepository $ownerRepository;

    /** @var AppointmentRepository */
    private AppointmentRepository $appointmentRepository;

    /** @var TokenStorageInterface */
    private TokenStorageInterface $tokenStorage;

    /**
     * @var KernelInterface
     */
    private KernelInterface $_appKernel;

    /** @var PrintFormHistoryService */
    private PrintFormHistoryService $reportFormService;

    private array $months = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];

    /**
     * PrintFormsController constructor.
     * @param EntityManagerInterface $entityManager
     * @param PrintFormsRepository $printFormsRepository
     * @param PetRepository $petRepository
     * @param OwnerRepository $ownerRepository
     * @param AppointmentRepository $appointmentRepository
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        PrintFormsRepository $printFormsRepository,
        PetRepository $petRepository,
        OwnerRepository $ownerRepository,
        AppointmentRepository $appointmentRepository,
        KernelInterface $appKernel,
        TokenStorageInterface $tokenStorage,
        PrintFormHistoryService $reportFormService
    )
    {
        $this->_appKernel = $appKernel;
        $this->entityManager = $entityManager;
        $this->printFormsRepository = $printFormsRepository;
        $this->petRepository = $petRepository;
        $this->ownerRepository = $ownerRepository;
        $this->appointmentRepository = $appointmentRepository;
        $this->tokenStorage = $tokenStorage;
        $this->reportFormService = $reportFormService;
    }

    /**
     * @deprecated
     * @Route("/form/{printFormId}/", methods={"POST"})
     *
     * @SWG\Post(
     *     description="Создание печатной формы",
     *     summary="Создание печатной формы",
     *     @SWG\Response(
     *         response="200",
     *         @SWG\JsonContent(),
     *         description="Создание печатной формы"
     *     ),
     *     @SWG\Parameter(
     *          parameter="body",
     *          name="body",
     *          in="body",
     *          description="Данные для создания печатной формы",
     *          @SWG\Schema(type="object", ref=@Model(type=CreatePrintFormRequest::class))
     *     )
     * )
     */
    public function form($printFormId, Request $request, BaseResponse $response, DeserializeService $deserializeService, PrintEngine $printEngine): Response
    {
        /** @var PrintForm $printForm */
        if (!$printForm = $this->printFormsRepository->findPrintForm($printFormId)) {
            throw new NotFoundHttpException('print_forms.print.not_found');
        }

        /** @var CreatePrintFormRequest $createPrintFormRequest */
        $createPrintFormRequest = $deserializeService->deserialize($request->getContent(), CreatePrintFormRequest::class, 'json');
        $owner = null;
        if ($createPrintFormRequest->ownerId) {
            if (!$owner = $this->ownerRepository->find($createPrintFormRequest->ownerId)) {
                throw new NotFoundHttpException('print_forms.owner.not_found');
            }
        }

        $pet = null;
        if ($createPrintFormRequest->petId) {
            if (!$pet = $this->petRepository->find($createPrintFormRequest->petId)) {
                throw new NotFoundHttpException('print_forms.pet.not_found');
            }
        }

        $appointment = null;
        if ($createPrintFormRequest->appointmentId) {
            if (!$appointment = $this->appointmentRepository->find($createPrintFormRequest->appointmentId)) {
                throw new NotFoundHttpException('print_forms.appointment.not_found');
            }
        }

        $file = $printForm->getFile();
        $directory = $this->getParameter('kernel.project_dir') . '/../public/' . Env::getenv('UPLOAD_FILE_PUBLIC_DIR');

        $params = [];
        if ($appointment) {
            $params['appointment'] = $appointment;
        } else {
            $params = [
                'owner' => $owner,
                'pet' => $pet,
            ];
        }
        $params['user'] = $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;
        $params['datetime'] = new DateTime();

        $newContent = $printEngine->processPrintForm($printForm, $params);
        if (!$newContent) {
            throw new BadRequestHttpException('print_form.file.mime_type');
        }

        $fileName = $file->getName();
        $fileExtension = explode('.', $fileName)[1];

        $this->entityManager->getConnection()->setAutoCommit(false);
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $generatedFileName = Uuid::uuid4() . '.' . $fileExtension;
            $generatedFile = $directory . '/' . $generatedFileName;
            file_put_contents($generatedFile, $newContent);

            $generatedUploadedFile = clone $file;
            $generatedUploadedFile->setType(UploadedFile::TYPE_PRINT_FORM);
            $generatedUploadedFile->setName($generatedFileName);
            $generatedUploadedFile->setRelativePath($this->generateUrl('app_uploadedfile_view', ['name' => $generatedFileName]));
            $this->entityManager->persist($generatedUploadedFile);
            $this->entityManager->flush();
            $this->entityManager->commit();

            if ($pet) {
                $petFile = new File();
                $petFile->setName($printForm->getName());
                $petFile->setPet($pet);
                $petFile->setUploadedFile($generatedUploadedFile);
                $petFile->setCreatedAt(new DateTime());

                $this->entityManager->persist($petFile);
                $this->entityManager->flush();
                $this->entityManager->commit();
            }

            if ($owner) {
                $ownerFile = new FileOwner();
                $ownerFile->setName($printForm->getName());
                $ownerFile->setOwner($owner);
                $ownerFile->setUploadedFile($generatedUploadedFile);
                $ownerFile->setCreatedAt(new DateTime());

                $this->entityManager->persist($ownerFile);
                $this->entityManager->flush();
                $this->entityManager->commit();
            }
        } catch (Exception $exception) {
            if (isset($generatedFile)) {
                @unlink($generatedFile);
            }

            $this->entityManager->rollback();

            throw $exception;
        }

        return $response->setResponse($generatedUploadedFile)->send();
    }

    /**
     * @Route("/printFormsList/", methods={"GET", "POST"})
     *
     * @SWG\Get(
     *     tags={"File"},
     *     description="Вывести все печатные формы для данной площадки",
     *     summary="Получить список всех печатных форм",
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Успешный ответ сервиса"
     *     )
     * )
     * @param Request $request
     * @param BaseResponse $response
     * @param TranslatorInterface $translator
     * @return Response
     * @throws \App\Exception\ApiException
     */
    public function ls(Request $request, BaseResponse $response, TranslatorInterface $translator): Response
    {
        $fullPathToRootDir = $this->_getDirectory();

        if ($request->isMethod('POST')) {

            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            if (!$file = $request->files->get('file')) {
                return $response->statusError()
                    ->addError(new ApiException($translator->trans('file.not_found')))
                    ->send();
            }

            if ($file->getClientOriginalExtension() !== 'docx') {
                return $response->statusError()
                    ->addError(new ApiException('Некорректный формат файла. Нужен формат DOCX'))
                    ->send();
            }

            $file->move($fullPathToRootDir, $request->get('name') . '.' . $file->getClientOriginalExtension());

            return $response->setResponse([
                'status' => true
            ])->setSerializationContext(['groups' => ['default']])->send();
        }

        try {
            $list = scandir($fullPathToRootDir);
        } catch (Exception $e) {
            throw new ApiException('print_forms.domain_code.not_found', 'No such directory', null, 500);
        }

        unset($list[array_search('.', $list)]);
        unset($list[array_search('..', $list)]);
        sort($list);
        $data = [
            'files' => $list
        ];

        return $response->setResponse($data)->setSerializationContext(['groups' => ['default']])->send();
    }


    /**
     * @Route("/download/", methods={"GET"})
     *
     * @SWG\Get(
     *     @SWG\Response(
     *         response=200,
     *         description="Успешный ответ сервиса"
     *     )
     * )
     * @param Request $request
     * @param BaseResponse $response
     * @return Response
     * @throws \App\Exception\ApiException
     */
    public function printForm(Request $request, BaseResponse $response)
    {
        $id = $request->get('id');
        $type = mb_strtolower($request->get('type'));
        $type_id = $request->get('type_id');

        if (is_null($id)) {
            return $response->statusError()
                ->addError(new ApiException('Укажите ID шаблона для печати'))
                ->send();
        }

        if (is_null($type)) {
            return $response->statusError()
                ->addError(new ApiException('Укажите тип, для которого производится печать'))
                ->send();
        }

        if (is_null($type_id)) {
            return $response->statusError()
                ->addError(new ApiException('Укажите ID выбранного типа'))
                ->send();
        }

        switch ($type) {
            case 'appointment':
                $entity = $this->entityManager->getRepository(Appointment::class)
                    ->findOneBy(['id' => $type_id]);
                break;
            case 'owner':
                $entity = $this->entityManager->getRepository(Owner::class)
                    ->findOneBy(['id' => $type_id]);
                break;
            case 'pet':
                $entity = $this->entityManager->getRepository(Pet::class)
                    ->findOneBy(['id' => $type_id]);
                break;
            default:
                return $response->statusError()
                    ->addError(new ApiException('Некорректный тип ' . $type))
                    ->send();
        }


        if (is_null($entity)) {
            return $response->statusError()
                ->addError(new ApiException('Не найдена запись по указанному ID типа'))
                ->send();
        }

        $printTemplate = $this->entityManager->getRepository(PrintForm::class)->findOneBy([
            'id' => $id
        ]);

        if (is_null($printTemplate)) {
            return $response->statusError()
                ->addError(new ApiException('Не найден шаблон для печати'))
                ->send();
        }

        $fullPathToRootDir = $this->_getDirectory();

        $name = $printTemplate->getOriginFileName();

        try {
            $templateProcessor = new TemplateProcessor($fullPathToRootDir . $name);
        } catch (CopyFileException | CreateTemporaryFileException $e) {
            return $response->statusError()
                ->addError(new ApiException($e->getMessage()))
                ->send();
        }


        $vars = $templateProcessor->getVariableCount();

        foreach ($vars as $key => $var) {
            preg_match_all('/[a-zA-Z]+/', $key, $output_array);
            $output_array = $output_array[0];

            try {
                $var = $this->_parseVars($output_array, $entity, $type);
            } catch (ApiException $e) {
            }

            if (!is_null($var)) {
                if ($key == 'appointment#prescription') {
                    $vars[$key] = trim(preg_replace('~[\n]+~', "</w:t>\n<w:br />\n<w:t xml:space=\"preserve\">", $var));
                } else {
                    $vars[$key] = trim(preg_replace('~[\r\n]+~', ' ', $var));
                }
                if($key == 'appointment#appointmentWeight#weight#value'){
                    if($var === 0){
                        $vars[$key]='__________________________';
                    }
                    else{
                        $vars[$key] = $vars[$key]/1000;
                    }
                }
            }
        }

        if (!empty($request->get('not_download'))) {
            dd('Переменные:', $vars);
        }

        $name_ext = [];
        foreach ($vars as $key => $var) {
            $templateProcessor->setValue('${' . $key . '}', ($var === 1) ? '__________________________' : $var);

            if ((str_contains($key, "pet#breed#type#name") || str_contains($key, "pet#type#name")) && !empty($var)) {
                $name_ext[0] = str_replace([' ', '/'], '_', $var);
            }

            if (str_contains($key, "pet#name") && !empty($var)) {
                $name_ext[1] = str_replace([' ', '/'], '_', $var);
            }

            if (str_contains($key, "owner#name") && !empty($var)) {
                $name_ext[2] = str_replace([' ', '/'], '_', $var);
            }
        }

        foreach ($name_ext as $i => $item) {
            if (!empty($item)) {
                $name = str_replace('.', "_$item.", $name);
            }
        }

        $name = preg_replace('/\s+/', '_', $name);

        $templateProcessor->saveAs("/tmp/$name");

        if (!empty($request->get('pdf'))) {
            exec("libreoffice --headless -convert-to pdf /tmp/$name -outdir /tmp/", $output_array);
            $name = preg_replace('/docx/', 'pdf', $name);
        }

        $response = new BinaryFileResponse("/tmp/$name");

        
        try {
            $this->reportFormService->AddChanges($printTemplate->getName());
        } finally {
            if(!empty($request->get('pdf'))){
                $response->headers->set('Content-Type', 'application/pdf');
                $response->setContentDisposition(
                    ResponseHeaderBag::DISPOSITION_INLINE, //use ResponseHeaderBag::DISPOSITION_ATTACHMENT to save as an attachement
                    $name
                );
            } else {
                $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
            }
    
            return $response;
        }
    }

    /**
     * @param array $output_array
     * @param $entity
     * @param string $type
     * @return mixed|null |null
     * @throws \App\Exception\ApiException
     */
    private function _parseVars(array $output_array, $entity, string $type)
    {
        $tmp = $entity;
        if ($output_array[0] === $type) {
            $tmp = $this->_foreachMethods($output_array, $tmp);
        } else {
            switch ($output_array[0]) {
                case 'pet':
                    if ($type !== 'appointment') {
                        if ($type !== 'owner') {
                            throw new ApiException('Некорректный используется тип PET');
                        }

                        if (count($tmp->getPets()) > 1) {
                            throw new ApiException('Укажите конкретное животное');
                        }

                        foreach ($tmp->getPets() as $pet) {
                            $entity = $pet;
                            break;
                        }
                    }
                    $tmp = $this->_foreachMethods($output_array, $entity->getPet());
                    break;

                case 'date':
                    $tmp = $this->_foreachMethods($output_array, null);
                    break;

                case 'user':
                    $tmp = $this->_foreachMethods($output_array, $this->getUser());
                    break;

                case 'appointment':
                    # удаляем appointment
                    unset($output_array[0]);

                    $output_array = array_values($output_array);

                    # если документ печатается со страницы владельца
                    if ($type === 'owner') {
                        if (count($tmp->getPets()) > 1) {
                            throw new ApiException('Укажите конкретное животное');
                        }

                        foreach ($tmp->getPets() as $pet) {
                            $entity = $pet;
                            break;
                        }

                        switch ($output_array[0]) {
                            # если из приема нужен питомец
                            case 'pet':
                                $tmp = $this->_foreachMethods($output_array, $entity->getPet());
                                break;

                            case 'owner':
                                $tmp = $this->_foreachMethods($output_array, $tmp);
                                break;

                            # если из приема нужна клиника, возьмем последний прием этого владельца
                            case 'user':
                            case 'unit':
                            case 'date':
                            case 'prescription':
                                foreach ($entity->getPet()->getAppointments() as $appointment) {
                                    $entity = $appointment;
                                    break;
                                }

                                if ($output_array[0] === 'unit') {
                                    $entity = $entity->getUnit();
                                } elseif ($output_array[0] === 'user') {
                                    $entity = $entity->getUser();
                                }

                                $tmp = $this->_foreachMethods($output_array, $entity);
                                break;
                        }
                    } elseif ($type === "pet") {

                        switch ($output_array[0]) {
                            # если из приема нужен питомец
                            case 'pet':
                                $tmp = $this->_foreachMethods($output_array, $tmp);
                                break;

                            case 'owner':
                                foreach ($tmp->getOwners() as $owner) {
                                    $entity = $owner->getOwner();
                                    break;
                                }
                                $tmp = $this->_foreachMethods($output_array, $entity);
                                break;

                            # если из приема нужна клиника, возьмем последний прием этого владельца
                            case 'user':
                            case 'unit':
                            case 'date':
                            case 'prescription':
                                foreach ($entity->getAppointments() as $appointment) {
                                    $entity = $appointment;
                                    break;
                                }

                                if ($output_array[0] === 'unit') {
                                    $entity = $entity->getUnit();
                                } elseif ($output_array[0] === 'user') {
                                    $entity = $entity->getUser();
                                }

                                $tmp = $this->_foreachMethods($output_array, $entity);
                                break;
                        }
                    }

                    break;

//                default:
//                    dd($output_array[0]);
            }
        }

        return !is_null($tmp) ? $this->_getValueForVariables($tmp, $output_array) : null;
    }

    /**
     * @param $output_array
     * @param $tmp
     * @return Owner|string|null
     */
    private function _foreachMethods($output_array, $tmp)
    {
        $date = new DateTime();
        foreach ($output_array as $index => $item) {
            if ($index == 0 && count($output_array) > 1) continue;

            switch ($item) {
                case 'template':
                    /** @var Appointment $tmp */
                    $template = $tmp->getAppointmentFormTemplate();
                    $values = [];
                    $template_text = [];

                    /** @var AppointmentFormTemplate $value */
                    foreach ($template->getValues() as $i => $value) {
                        $template_text[$i] = $value->getFormTemplate()->getTemplate();
                        foreach ($value->getFormFieldValues() as $formFieldValue) {
                            $values[$i][] = [
                                'value' => $formFieldValue->getValue(),
                                'data' => $formFieldValue->getExtraData()
                            ];
                        }
                    }

                    $text = [];

                    foreach ($template_text as $a => $txt) {
                        $data = preg_split('/\n/', $txt);
                        foreach ($data as $i => $datum) {
                            if(empty($datum)) continue;

                            preg_match('/\d+-\d+/', $datum, $ids);

                            if (count($ids)) {
                                $ids = preg_split('/-/', $ids[0]);
                                $arr = $values[$a][$ids[1] - 1];

                                if (empty($arr['value'])) {
                                    $text[] = preg_replace('/({{{.*}}})/', $arr['value'], preg_replace('/\s{3,}/', '', $datum));
                                    continue;
                                }

                                if (!is_object(json_decode($arr['value'])) && is_null($arr['data']['crudType'])) {
                                    $text[] = preg_replace('/({{{.*}}})/', $arr['value'], preg_replace('/\s{3,}/', '', $datum));
                                } else {

                                    if (is_object(json_decode($arr['value']))) {
                                        $json = json_decode($arr['value'], true);
                                        foreach ($json as $elm) {
                                            if (empty($elm)) continue;

                                            $text[] = preg_replace('/({{{.*}}})/', $elm, preg_replace('/\s{3,}/', '', $datum));
                                        }
                                    } else {
                                        if(!is_null($arr['data']['filter'])){
                                            $entity = $this->entityManager->getRepository(TemplateReferenceValue::class)
                                                ->findOneBy(['id' => $arr['value']]);
                                        } else {
                                            $entity = preg_replace('/reference/', '', $arr['data']['crudType']);
                                            $entity = $this->entityManager->getRepository("App\Entity\Reference\\$entity")
                                                ->findOneBy(['id' => $arr['value']]);
                                        }

                                        if (is_null($entity)) continue;

                                        $text[] = preg_replace('/({{{.*}}})/', $entity->getName(), preg_replace('/\s{3,}/', '', $datum));
                                    }
                                }
                            } else {
                                $datum = strip_tags($datum);
                                $text[] = (empty($datum)) ? "\n" : $datum;
                            }
                        }
//                        dd($text);
                    }

                    $tmp = strtr(implode("\n", $text), [
                        "\n" => "</w:t>\n<w:br />\n<w:t xml:space=\"preserve\">"
                    ]);
                    break 2;

                case 'owner':
                    if ($output_array[0] !== 'appointment') {
                        /** @var PetToOwner $petToOwner */
                        foreach ($tmp->getOwners() as $petToOwner) {
                            if ($petToOwner->isMainOwner()) {
                                $tmp = $petToOwner->getOwner();
                            }
                        }
                        continue 2;
                    }
                    break;
                case 'detailformat':
                    $f = $this->months[(int)$date->format('m') - 1];
                    $tmp = $date->format('d') . " $f " . $date->format('Y');
                    break 2;
                case 'monthWord':
                    $tmp = $this->months[(int)$date->format('m') - 1];
                    break 2;
                case 'year':
                    $tmp = $date->format('Y');
                    break 2;
                case 'day':
                    $tmp = $date->format('d');
                    break 2;
            }

            try {
                $method = "get" . ucfirst($item);
                $tmp = $tmp->$method();
            } catch (Throwable $exception) {
                $tmp = null;
            }
        }

        return $tmp;
    }

    /**
     * @param $tmp
     * @param array $output_array
     * @return mixed
     */
    private function _getValueForVariables($tmp, array $output_array)
    {
        try {
            if ($output_array[count($output_array) - 1] === 'date') {
                return $tmp->format('Y-m-d');
            } else {
                return $tmp;
            }
        } catch (Throwable $exception) {
//            dd($output_array, $tmp, $exception->getMessage());
            return null;
        }
    }

    /**
     * @return string
     * @throws ApiException
     */
    private function _getDirectory(): string
    {
        $fullPathToRootDir = $this->_appKernel->getProjectDir();

        $domain = $this->entityManager->getRepository(Settings::class)->findOneBy(['key' => 'domain.code']);

        if (strcasecmp($domain->getValue(), '') == 0) {
            throw new ApiException('Не найдена настройка для текущего домена');
        }

        $pathToRootDir = '/public/docs/printingForms/' . $domain->getValue() . '/';

//        $this->getParameter('kernel.project_dir') | /var/www/public
        return $fullPathToRootDir . $pathToRootDir;
    }
}
