<?php

namespace App\Packages\Security;

use Exception;
use ParagonIE\Paseto\Exception\PasetoException;
use ParagonIE\Paseto\Keys\SymmetricKey;
use ParagonIE\Paseto\Protocol\Version2;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\InvalidArgumentException;
use RuntimeException;
use SodiumException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Model\AuthenticationInformation;

class Encryptor
{
    /** @var SecretStorageProvider */
    private SecretStorageProvider $secretProvider;

    /** @var TokenStorageInterface */
    private TokenStorageInterface $tokenStorage;

    /** @var LoggerInterface */
    private LoggerInterface $logger;

    /** @var SerializerInterface */
    private SerializerInterface $serializer;

    /**
     * Encryptor constructor.
     *
     * @param SecretStorageProvider $secretProvider
     * @param TokenStorageInterface $tokenStorage
     * @param LoggerInterface       $logger
     * @param SerializerInterface   $serializer
     */
    public function __construct(SecretStorageProvider $secretProvider, TokenStorageInterface $tokenStorage, LoggerInterface $logger, SerializerInterface $serializer)
    {
       $this->tokenStorage = $tokenStorage;
       $this->secretProvider = $secretProvider;
       $this->logger = $logger;
       $this->serializer = $serializer;
    }

    /**
     * @param string $string
     *
     * @return string
     * @throws InvalidArgumentException
     * @throws PasetoException
     * @throws SodiumException
     */
    public function encrypt(string $string): string
    {
        $secretKey = $this->createKey();

        return Version2::encrypt($string, $secretKey);
    }

    /**
     * @param string $string
     *
     * @return string
     * @throws InvalidArgumentException
     * @throws PasetoException
     * @throws SodiumException
     */
    public function dencrypt(string $string): string
    {
        $secretKey = $this->createKey();

        return Version2::decrypt($string, $secretKey);
    }

    /**
     * @param AuthenticationInformation $decryptedAuthenticationInformation
     *
     * @return string
     * @throws InvalidArgumentException
     * @throws PasetoException
     * @throws SodiumException
     */
    public function encryptAuthenticationInformation(AuthenticationInformation $decryptedAuthenticationInformation): string
    {
        return $this->encrypt($this->serializer->serialize($decryptedAuthenticationInformation, 'json', ['groups' => 'encrypt_token.v1']));
    }

    /**
     * @param $encryptedAuthenticationInformation
     *
     * @throws PasetoException
     * @throws SodiumException
     *
     * @return AuthenticationInformation
     */
    public function decryptAuthenticationInformation($encryptedAuthenticationInformation): AuthenticationInformation
    {
        $secretKey = $this->createKey();

        $decryptedAuthenticationInformation = Version2::decrypt($encryptedAuthenticationInformation, $secretKey);
        $data = $this->serializer->deserialize($decryptedAuthenticationInformation, AuthenticationInformation::class, 'json', ['groups' => 'encrypt_token.v1']);

        if (!$data instanceof AuthenticationInformation) {
            throw new RuntimeException('Invalid data deserialized');
        }

        return $data;
    }

    /**
     * @return string|null
     */
    public function encryptCurrentAuthenticationInformation(): ?string
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return null;
        }

        if (!$token->hasAttribute('authentication_information')) {
            return null;
        }

        try {
            return $this->encryptAuthenticationInformation($token->getAttribute('authentication_information'));
        } catch (Exception $exception) {
            $this->logger->error($exception);

            return null;
        }
    }

    /**
     * @return AuthenticationInformation
     */
    public function getCurrentAuthenticationInformation(): ?AuthenticationInformation
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return null;
        }

        if (!$token->hasAttribute('authentication_information')) {
            return null;
        }

        return $token->getAttribute('authentication_information');
    }

    /**
     * @return SymmetricKey
     * @throws InvalidArgumentException
     */
    private function createKey(): SymmetricKey
    {
        return new SymmetricKey($this->secretProvider->findEncodingUserKey());
    }
}
