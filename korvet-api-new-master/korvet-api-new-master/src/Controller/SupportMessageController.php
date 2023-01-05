<?php

namespace App\Controller;

use App\Model\Env;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use Symfony\Component\HttpFoundation\Request;
use App\Exception\ApiException;
use App\Entity\User\User;
use App\Entity\Reference\Unit;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\TelegramBotService;
use App\Packages\Response\BaseResponse;

class SupportMessageController extends AbstractController
{
    /** @var TelegramBotService */
    private TelegramBotService $telegram;

    public function __construct(TelegramBotService $telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * @param Request $request
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $em
     * @param BaseResponse $response
     * @return \Symfony\Component\HttpFoundation\Response|void
     * @throws \App\Exception\ApiException
     * @Route("/api/support-message", methods={"POST"})
     */
    public function sendMessage(Request $request, TokenStorageInterface $tokenStorage, EntityManagerInterface $em, BaseResponse $response)
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['message'])) {
            throw new ApiException('Заполните текст сообщения');
        }
        /** @var User $currentUser */
        $currentUser = $tokenStorage->getToken()->getUser();
        if (isset($currentUser)) {
            $user = $currentUser->getSurname() . ' ' . $currentUser->getName() . ' ' . $currentUser->getPatronymic() . '(' . $currentUser->getUsername() . ')';
            if ($currentUser->getPhoneNumber() !== null) {
                $user .= ' тел.: ' . $currentUser->getPhoneNumber();
            }
            if ($currentUser->getEmail() !== null) {
                $user .= ' email: ' . $currentUser->getEmail();
            }
            /** @var Unit $user */
            $unit = $em->getRepository(Unit::class)->findOneBy([
                'id' => $currentUser->getUnit()->getId()
            ]);

            $unitName = $unit->getName();
            if ($unit->getPhone() !== null) {
                $unitName .= ' тел.: ' . $unit->getPhone();
            }
            if ($unit->getEmail() !== null) {
                $unitName .= ' email: ' . $unit->getEmail();
            }

            $message = "*Пользователь*: " . $user . "%0A%0A";
            $message .= "*Клиника*: " . $unitName . "%0A%0A";
            $message .= "*Url*: " . $data['url'] . "%0A%0A";
            $message .= "*Сообщение*: " . $data['message'] . "%0A%0A";

            if (isset($data['filePath'])) {
                $this->telegram
                    ->setMarkDown(true)
                    ->setChannel(Env::getenv('TELEGRAM_SUPPORT_CHAT_ID'))
                    ->send_photo($message, $data['filePath']);
            } else {
                $this->telegram
                    ->setMarkDown(true)
                    ->setChannel(Env::getenv('TELEGRAM_SUPPORT_CHAT_ID'))
                    ->send_message($message);
            }

            return $response->setResponse("Сообщение успешно отправлено")->send();
        }
    }
}
