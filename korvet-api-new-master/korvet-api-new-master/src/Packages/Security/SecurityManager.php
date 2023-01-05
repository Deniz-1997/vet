<?php

namespace App\Packages\Security;

use Exception;
use FOS\OAuthServerBundle\Security\Authentication\Token\OAuthToken;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Model\AuthenticationInformation;
use App\Model\User;

/**
 * Class SecurityManager
 */
class SecurityManager
{
    const ACCESS_TOKEN_MODE = 'access_token';

    const STATELESS_TOKEN_MODE = 'stateless';

    /** @var TokenStorageInterface */
    private TokenStorageInterface $tokenStorage;

    /** @var Encryptor */
    private Encryptor $encryptor;

    /** @var SerializerInterface */
    private SerializerInterface $serializer;

    /** @var PropertyInfoExtractorInterface */
    private PropertyInfoExtractorInterface $propertyInfoExtractor;

    /**
     * SecurityManager constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param Encryptor $encryptor
     * @param SerializerInterface $serializer
     * @param PropertyInfoExtractorInterface $propertyInfoExtractor
     */
    public function __construct(TokenStorageInterface $tokenStorage, Encryptor $encryptor, SerializerInterface $serializer, PropertyInfoExtractorInterface $propertyInfoExtractor) {
        $this->tokenStorage = $tokenStorage;
        $this->encryptor = $encryptor;
        $this->serializer = $serializer;
        $this->propertyInfoExtractor = $propertyInfoExtractor;
    }

    /**
     * @return TokenStorageInterface
     */
    public function getTokenStorage(): TokenStorageInterface
    {
        return $this->tokenStorage;
    }

    /**
     * @return Encryptor
     */
    public function getEncryptor(): Encryptor
    {
        return $this->encryptor;
    }

    /**
     * @param int    $mode
     * @param string $default
     * @return string
     */
    public function getAuthenticationToken($mode = self::STATELESS_TOKEN_MODE, $default = null): ?string
    {
        /** @var AuthenticationInformation $authenticationInformation */
        $authenticationInformation = $this->encryptor->getCurrentAuthenticationInformation();
        if (!$authenticationInformation) {
            return $default;
        }

        switch ($mode) {
            case self::STATELESS_TOKEN_MODE:
                return $this->encryptor->encryptCurrentAuthenticationInformation();
            case self::ACCESS_TOKEN_MODE:
                return $authenticationInformation->getAccessToken();
            default:
                return $default;
        }
    }

    /**
     * @return AuthenticationInformation|null
     */
    public function getAuthenticationInformation(): ?AuthenticationInformation
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            return null;
        }

        if ($token->hasAttribute('authentication_information')) {
            return $token->getAttribute('authentication_information');
        }

        if (!class_exists('FOS\OAuthServerBundle\Security\Authentication\Token\OAuthToken')) {
            return null;
        }

        if (!$token instanceof OAuthToken) {
            return null;
        }

        $tokenUser = $token->getUser();
        if (!$tokenUser instanceof User) {
            $pa = PropertyAccess::createPropertyAccessor();
            $user = new User();

            foreach ($this->propertyInfoExtractor->getProperties(get_class($tokenUser)) as $propertyTokenUser) {
                foreach ($this->propertyInfoExtractor->getProperties(get_class($tokenUser)) as $userProperty) {
                    if ($propertyTokenUser !== $userProperty) {
                        continue;
                    }

                    if (!$pa->isReadable($tokenUser, $propertyTokenUser) || !$pa->isWritable($user, $propertyTokenUser)) {
                        continue;
                    }

                    try {
                        $pa->setValue($user, $propertyTokenUser, $pa->getValue($tokenUser, $propertyTokenUser));
                    } catch (Exception $exception) {
                        continue;
                    }
                }
            }
        } else {
            $user = $tokenUser;
        }

        $authenticationInformation = new AuthenticationInformation();
        $authenticationInformation->setUser($user);
        $authenticationInformation->setAccessToken($token->getToken());
        $authenticationInformation->setClientId('');

        return $authenticationInformation;
    }

    /**
     * @param array $data
     * @return AuthenticationInformation|array|object|null
     */
    public function getAuthenticationInformationFromData(array $data) : ?AuthenticationInformation
    {
        return $this->serializer->deserialize(
            json_encode($data), 
            AuthenticationInformation::class, 
            'json', 
            ['groups' => ['default', 'encrypt_token.v1']]
        );
    }

    /**
     * @return array|null
     */
    public function getAuthenticationInformationData() : ?array
    {
        if (!$authenticationInformation = $this->getAuthenticationInformation()) {
            return null;
        }

        return json_decode(
            $this->serializer->serialize($authenticationInformation, 'json', ['groups' => ['encrypt_token.v1']]),
            true
        );
    }
}
