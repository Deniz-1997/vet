<?php

namespace App\Service\WebSocket;

use App\Entity\Notifications\NotificationsList;
use Exception;
use DateTime;
use Psr\Log\LoggerInterface;
use App\Exception\ApiException;
use App\Entity\User\User;
use App\Repository\User\UserRepository;
use App\Service\CasApi\ApiAuthService;

class WebSocketClient
{
     /** @var  ApiAuthService */
     private ApiAuthService $apiAuthService;

     /** @var  UserRepository */
     private UserRepository $userRepository;

    private $userId;

    public function __construct(ApiAuthService $apiAuthService, UserRepository $userRepository)
    {
        $this->apiAuthService = $apiAuthService;
        $this->userRepository =  $userRepository;

        $this->apiAuthService->apiUrl = getenv('WEB_SOCKET_API_URL');
        $this->apiAuthService->apiAuthUrl = getenv('WEB_SOCKET_API_AUTH');
        $this->apiAuthService->apiLogin = getenv('WEB_SOCKET_API_LOGIN');
        $this->apiAuthService->apiPassword = getenv('WEB_SOCKET_API_PASSWORD');
        $this->apiAuthService->serverCode = 'SOCKET';
        $this->apiAuthService->apiClientId = null;
        $this->apiAuthService->casApiClientSecret = null;
    }

    public function sendNotification(NotificationsList $notification)
    {
        if (!$this->apiAuthService->apiAuthUrl || !$this->apiAuthService->apiLogin || !$this->apiAuthService->apiPassword) {
            return null;
        }
        $body = [];
        foreach ($notification->getToSend() as $item) {
            if ( $item->getViewed() == false) {
                $body['header'] = $notification->getHeader();
                $body['text'] = $notification->getData();
                $body['send_at'] = $item->getCreatedAt()->format(DateTime::ATOM);
                $body['channel'] = 'WEB_SOCKET';
                $body['text']['type'] = 'updateClientNotificationsList';
                 /** @var User $user */
                $user = $this->userRepository->findOneBy(['id' => $item->getValue()]);
                $body['consumer'] = [
                    'id' => "{$user->getId()}",
                    'name' => "{$user->getName()}",
                    'surname' => "{$user->getSurname()}",
                    'patronymic' => "{$user->getPatronymic()}",
                    'user_name' => "{$user->getUsername()}",
                    'email' => "{$user->getEmail()}",
                    'phone_number' => "{$user->getPhoneNumber()}",
                ];
            }
        }
        if (count($body) == 0) {
            return;
        }
        $data=json_encode([$body]);
        $token = $this->apiAuthService->getAuthToken();
        $response = $this->apiAuthService->httpClientInterface->request(
            'POST',
            "{$this->apiAuthService->apiUrl}notification",
            [
                'headers' => [
                    'User-Agent' => 'PHP console app',
                    'Content-type' => 'application/json',
                    'Authorization' => "Bearer " . $token,
                    'Host' => $_SERVER['HTTP_HOST'],
                    'Content-Length' =>strlen($data)
                ],
                'body' => $data
            ]
        );

        if ($response) {
            $code = $response->getStatusCode();
            if ($response->getStatusCode() == 401) {
                $this->apiAuthService->refreshAuthToken();
                return $this->sendNotification($notification);
            }
            if ($response->getStatusCode() == 400) {
                throw new ApiException("Не удалось отправить уведомление", 'SEND_ERROR', null, 400);
            }
            $content = json_decode($response->getContent(), true);
            if (isset($content['status']) && isset($content["response"]) && $content["status"] == true) {
                return $content["status"];
            }
        }
        return null;
    }
}
