<?php

namespace App\Packages\EventSubscriber;

use App\Entity\OAuth\AccessToken;
use App\Entity\OAuth\RefreshToken;
use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Packages\EventDispatcher\EventRequest;
use App\Packages\Response\BaseResponse;

/**
 * Class UserSubscriber
 */
class UserSubscriber implements EventSubscriberInterface
{
    /** @var BaseResponse  */
    private $response;

    /** @var UserPasswordEncoderInterface  */
    private $passwordEncoder;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * UserUpdate constructor.
     * @param BaseResponse $response
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        BaseResponse $response,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager
    ) {
        $this->response = $response;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return array(
            "onBeforeSaveEntityAppEntityUserUserPatch" => 'patchOnBeforeSave'
        );
    }

    /**
     * @param EventRequest $event
     */
    public function patchOnBeforeSave(EventRequest $event)
    {
        /** @var User $user */
        $user = $event->getData();
        if (!is_null($user->getPlainPassword()) && strlen($user->getPlainPassword())>0) {
            // update user password
            $user->setSalt(sha1(time()));
            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, $user->getPlainPassword())
            );

            // удаление всех токенов, связанных с пользователем
            $this->entityManager->getRepository(RefreshToken::class)->removeTokensByUser($user);
            $this->entityManager->getRepository(AccessToken::class)->removeTokensByUser($user);
        }
    }
}
