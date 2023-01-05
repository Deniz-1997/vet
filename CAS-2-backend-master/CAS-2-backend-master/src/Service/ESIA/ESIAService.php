<?php

namespace App\Service\ESIA;

use App\Service\ESIA\EsiaProvider;
use App\Service\ESIA\OpensslPkcs7;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reference\BusinesEntity;
use App\Entity\User\User;
use App\Packages\DTO\UserAdditionalFields;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Packages\OAuth\Storage\OAuthStorage;
use App\Packages\OAuth\OAuth2;
use App\Entity\Security\Group;
use App\Interfaces\ESIA\ESIAInterface;
use App\Exception\ApiException;
use Psr\Log\LoggerInterface;

class ESIAService implements ESIAInterface
{
    protected $provider;
    /**
     * @var UserPasswordEncoderInterface
     */
    protected UserPasswordEncoderInterface $passwordEncoder;
    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $defaultEntityManager;
    /**
     * @var OAuthStorage
     */
    protected OAuthStorage $oauthStorage;
     /**
     * @var LoggerInterface
     */
    protected $logger;

    protected string $esiaClientId;
    protected string $esiaRedirecrUrl;
    protected string $esiaRemoteUrl;
    protected string $esiaCertPath;
    protected string $esiaKeyPath;
    protected string $esiaKeyPassword;
    private bool $isProd;

    /**
     * ESIAService constructor.
     * @param EntityManagerInterface $defaultEntityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param OAuthStorage $oauthStorage
     */
    public function __construct(
        EntityManagerInterface $defaultEntityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        OAuthStorage $oauthStorage,
        LoggerInterface $logger
    ) {
        $this->defaultEntityManager = $defaultEntityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->oauthStorage = $oauthStorage;
        $this->logger = $logger;

        $this->esiaClientId = getenv('ESIA_CLIENT_ID');
        $this->esiaRedirecrUrl = getenv('ESIA_REDIRECT_URL');
        $this->esiaRemoteUrl = getenv('ESIA_REMOTE_URL');
        $this->esiaCertPath = getenv('ESIA_CERT_PATH');
        $this->esiaKeyPath = getenv('ESIA_KEY_PATH');
        $this->esiaKeyPassword = getenv('ESIA_KEY_PASSWORD');
        $this->isProd = getenv('APP_ENV') === 'prod';

        $this->provider = new EsiaProvider(
            [
                'clientId'      => $this->esiaClientId,
                'redirectUri'   => $this->esiaRedirecrUrl,
                'defaultScopes' => ['openid', 'fullname', 'inn', 'usr_org', 'email', 'mobile'],
                'remoteUrl'     => $this->esiaRemoteUrl,
                //  'remoteCertificatePath' => EsiaProvider::RESOURCES.'esia.test.cer',
            ],
            [
                'signer' => new OpensslPkcs7(
                    $this->esiaCertPath,
                    $this->esiaKeyPath,
                    $this->esiaKeyPassword
                )
            ]
        );
    }

    public function GetAuthorizationUrl()
    {
        $authUrl = $this->provider->getAuthorizationUrl();
        $this->logger->notice($authUrl);
        $response = [
            'url' => $authUrl
        ];
        return $response;
    }

    public function AuthorizeUser(string $authorizeCode)
    {
        $esiaToken = $this->provider->getAccessToken($authorizeCode);
        $esiaPersonData = $this->provider->getOwnerInformation($esiaToken);
        if ($esiaPersonData != null) {
            $content = $this->provider->getOwnerOrganization($esiaToken);
            $userValid = !$this->isProd;
            if (isset($content) && isset($content["elements"]) && count($content['elements']) > 0) {
                foreach ($content['elements'] as $userOrg) {
                    $entity = $this->defaultEntityManager->getRepository(BusinesEntity::class)->findOneBy(['ogrn' => $userOrg['ogrn']]);
                    if ($entity != null) {
                        $userValid = true;
                    }
                }
            }
            if ($userValid) {
                $user = $this->defaultEntityManager->getRepository(User::class)->findOneBy(['externalId' => $esiaPersonData["resourceOwnerId"]]);
                if ($user == null) {
                    $user = new User();
                    foreach ($esiaPersonData["contacts"]["elements"] as $contact) {
                        if ($contact['type'] === 'MBT') {
                            $user->setPhoneNumber($contact['value']);
                        }
                        if ($contact['type'] === 'EML') {
                            $user->setEmail($contact['value']);
                            $user->setUsername($contact['value']);
                        }
                    }
                    $user->setName($esiaPersonData["firstName"]);
                    $user->setSurname($esiaPersonData["lastName"]);
                    $user->setPatronymic($esiaPersonData["middleName"]);
                    $user->setExternalId($esiaPersonData["resourceOwnerId"]);
                    $userAdditionalFields = new UserAdditionalFields();
                    $userAdditionalFields->inn = $esiaPersonData["inn"];
                    $user->setAdditionalFields([0 => $userAdditionalFields]);
                    $user->setPlainPassword(uniqid());
                    $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()));
                    $group = $this->defaultEntityManager->getRepository(Group::class)->findOneBy(['code' => 'ROLE_BUSINESS_ENTITY']);
                    if ($group != null) {
                        $user->addGroup($group);
                    }
                    $this->defaultEntityManager->persist($user);
                    $this->defaultEntityManager->flush();
                }
                //Добавляем ссылки ХС->Пользователь
                foreach ($content['elements'] as $userOrg) {
                    /** @var BusinesEntity */
                    $entity = $this->defaultEntityManager->getRepository(BusinesEntity::class)->findOneBy(['ogrn' => $userOrg['ogrn']]);
                    if ($entity != null) {
                        $entity->addUser($user);
                        $this->defaultEntityManager->persist($user);
                        $this->defaultEntityManager->flush();
                    }
                }
                $oauth = new OAuth2($this->oauthStorage);
                $tokens = $oauth->createDefaultAccessToken($this->oauthStorage->getClient(getenv('MICROSERVICE_CLIENT_ID')), $user);
                return $tokens;
            } else {
                throw new ApiException('Доступ запрещен. Организация не найдена в реестре хозяйствующих субъектов. Обратитесь в территориальное ветеринарное управление.', 'ACCESS_DENIED', null, 400);
            }
        }
    }
}
