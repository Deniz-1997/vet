<?php

namespace App\Controller;

use App\Entity\Cash\CashReceipt;
use App\Entity\DeviceCashboxMobile;
use App\Entity\Reference\Product;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Controller\ApiController;
use App\Packages\DBAL\Types\FiscalReceiptStateEnum;
use App\Entity\ReceiptItem;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;
use App\Packages\DBAL\Types\PaymentTypeEnum;

/**
 * Class ApiAqsiController.
 *
 * @Route("/api/aqsi")
 */
class ApiAqsiController extends ApiController
{
    const TOKEN = 'XxzAkBLt0V9SbWxrsAYCnAfAE8cpfiPPmNXF8LqKAJOmZvHgk2fIhv7jI1orOlaj';

    const URL = 'https://api.aqsi.ru/pub';

    /** @var EntityManagerInterface */
    private EntityManagerInterface $_entityManager;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $_client;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager)
    {
        $this->_entityManager = $entityManager;
        $this->_client = $client;
    }

    /**
     * Возвращаем заказ по ID (id чека)
     * @param int $id
     * @param BaseResponse $response
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ApiException
     * @Route("/order/{id}", methods={"GET"})
     */
    public function getOrder(int $id, BaseResponse $response)
    {
        $data = $this->request('/v2/Orders', 'GET', [
            'filtered.Search' => $id
        ]);

        if (!$data['status'] || !isset($data['data']['rows']) || !count($data['data']['rows'])) {
            throw new ApiException(sprintf('Не найден заказ по номеру #%s', $id));
        }

        $row = $data['data']['rows'][0];

        return $response->setResponse($row)->setSerializationContext(['groups' => ['default']])->send();
    }

    /**
     * Создаем заказ по ID(id чека)
     *
     * @param int $id
     * @param BaseResponse $response
     * @param Request $request
     * @return Response
     * @throws ApiException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws \Doctrine\DBAL\Exception
     * @Route("/order/{id}", methods={"POST"})
     */
    public function createOrders(int $id, BaseResponse $response, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['device_id'])) {
            throw new ApiException('Укажите на какой девайс отправить заказ');
        }

        $device_id = $data['device_id'];

        $device = $this->_entityManager->getRepository(DeviceCashboxMobile::class)->findOneBy([
            'deviceId' => $device_id
        ]);

        if(is_null($device)){
            throw new ApiException(sprintf('Девайс #%s не найден', $device_id));
        }

        $cashReceipt = $this->_entityManager->getRepository(CashReceipt::class)->findOneBy([
            'id' => $id
        ]);

        if (strcasecmp($cashReceipt->getFiscal()->getState(), FiscalReceiptStateEnum::DONE) === 0) {
            throw new ApiException(sprintf('Чек #%s отправлен на печать', $id));
        }

        if (!$cashReceipt) {
            throw new ApiException(sprintf('Чек #%s не найден в системе', $id));
        }

        $items = $this->_entityManager->getRepository(ReceiptItem::class)->findBy([
            'cashReceipt' => $cashReceipt
        ]);
        
        $paymentType = 0;
        switch ($cashReceipt->getPaymentType()) {
            case PaymentTypeEnum::ELECTRONICALLY:
                $paymentType = 1;
                break;
            case PaymentTypeEnum::PREPAID:
                $paymentType = 13;
                break;
            default:
                $paymentType = 0;
        }

        $data = [
            "id" => Uuid::uuid4()->toString(),
            "number" => $id,
            "dateTime" => $cashReceipt->getCreatedAt()->format('Y-m-d H:i:s'),
            "device" => $device_id,
            //            "comment" => "Pay pls",
            //            "deliveryAddress" => $cashReceipt->ge,
            //            "pickAddress" => "Earth",
            "content" => [
                "type" => 1,
                "positions" => [],
                "checkClose" => [
                    "payments" => [
                        [
                            "type" => $paymentType,
                            "amount" => $cashReceipt->getTotal()
                        ]
                    ],
                    "taxationSystem" => 0
                ]
            ],
            "isEditableByDevice" => true
        ];

        foreach ($items as $item) {
            $product = $this->_entityManager->getRepository(Product::class)->findOneBy([
                'id' => $item->getProduct()->getId()
            ]);

            switch ($product->getVatRate()) {
                case 'VAT_20':
                    $tax = 1;
                    break;
                case 'VAT_10':
                    $tax = 2;
                    break;
                case 'VAT_120':
                    $tax = 3;
                    break;
                case 'VAT_110':
                    $tax = 4;
                    break;
                case 'VAT_0':
                    $tax = 5;
                    break;
                default:
                    throw new ApiException(sprintf('Не определен данный НДС %s', $product->getVatRate()));
            }

            $data['content']['positions'][] = [
                "addedAt" => (new \DateTime())->format('Y-m-d H:i:s'),
                "quantity" => $item->getQuantity(),
                "price" => $item->getPrice(),
                "tax" => $tax,
                "text" => $product->getName(),
                "paymentMethodType" => 4,
                "paymentSubjectType" => 1,
            ];
        }

        if (!count($data['content']['positions'])) {
            throw new ApiException(sprintf('Не найдены товары для чека #%s', $id));
        }

        $resp = $this->request('/v1/Orders/simple', 'POST', $data);

        if (!isset($resp['data']['guid'])) {
            throw new ApiException(sprintf('Ошибка при отправки заказа на сервер. Не найден guid #%s. Детали: %s', $id, json_encode($resp)));
        }

        $cashReceipt->setStartPrintAt((new \DateTime()));

        $conn = $this->_entityManager->getConnection();

        $conn->executeUpdate('UPDATE cash.cash_receipt SET uuid_receipt_mobile = :guid, fiscal_state = :state WHERE id = :id', [
            'cash_register_id' => '00000000-0000-0000-0000-000000000000', # null uuid
            'guid' => $resp['data']['guid'],
            'id' => $id,
            'state' => 'PRINTING'
        ]);

        return $this->getOrder($id, $response);
    }

    /**
     * Возвращаем доступные девайсы пользователю
     *
     * @Route("/devices", name="aqsi_devices_list")
     * @param BaseResponse $response
     * @return Response
     * @throws ApiException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getDevices(BaseResponse $response)
    {
        $data = $this->request('/v1/Devices');

        if (!$data['status'] || !isset($data['data']['devices']) || !count($data['data']['devices'])) {
            throw new ApiException("Девайсы не найдены");
        }

        foreach ($data['data']['devices'] as $datum) {
            $cashReceipt = $this->_entityManager->getRepository(DeviceCashboxMobile::class)->findOneBy([
                'deviceSn' => $datum['deviceSn']
            ]);

            if (is_null($cashReceipt)) {
                $cashReceiptEntity = new DeviceCashboxMobile();
                $cashReceiptEntity->setDeviceSn($datum['deviceSn']);
                $cashReceiptEntity->setImei($datum['imei']);
                $cashReceiptEntity->setShopId(is_null($datum['shopId']) ? 0 : $datum['shopId']);
                $cashReceiptEntity->setDeviceId($datum['id']);
                $cashReceiptEntity->setName("Device #" . $datum['deviceSn']);
                $this->_entityManager->persist($cashReceiptEntity);
                $this->_entityManager->flush();
            }
        }

        return $response->setResponse($data)->setSerializationContext(['groups' => ['default']])->send();
    }

    /**
     * Запрос к AQSI сервер
     *
     *
     * @param string $endpoint
     * @param string $method
     * @param array $data
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function request(string $endpoint, string $method = 'GET', array $data = []): array
    {
        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'x-client-key' => 'Application XxzAkBLt0V9SbWxrsAYCnAfAE8cpfiPPmNXF8LqKAJOmZvHgk2fIhv7jI1orOlaj'
            ]
        ];

        if (count($data)) {
            if ($method === 'GET') {
                $options['query'] = $data;
            } else {
                $options['body'] = json_encode($data);
            }
        }

        $response = $this->_client->request(
            $method,
            self::URL . $endpoint, $options
        );

        $statusCode = $response->getStatusCode();

        if ($statusCode !== Response::HTTP_OK && $statusCode !== Response::HTTP_CREATED) {
            $body = $response->getContent(false);

            if ($statusCode === Response::HTTP_UNAUTHORIZED) {
                return ['status' => false, 'msg' => 'Требуется авторизация', 'code' => $statusCode, 'body' => $body];
            }

            if ($statusCode === Response::HTTP_NOT_FOUND) {
                return ['status' => false, 'msg' => 'Указанный поинт не найден', 'code' => $statusCode, 'body' => $body];
            }

            return ['status' => false, 'msg' => 'Неизвестная ошибка', 'code' => $statusCode, 'body' => $body];
        }

        return ['status' => true, 'msg' => 'Запрос выполнен успешно', 'data' => $response->toArray()];
    }
}
