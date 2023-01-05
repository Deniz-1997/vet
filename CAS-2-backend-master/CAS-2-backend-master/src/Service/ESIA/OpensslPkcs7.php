<?php

namespace App\Service\ESIA;

use App\Exception\ApiException;

class OpensslPkcs7
{
    protected $certificatePath;
    protected $privateKeyPath;
    protected $privateKeyPassword;

    /**
     * @param string $certificatePath
     * @param string $privateKeyPath
     * @param string $privateKeyPassword
     */
    public function __construct($certificatePath, $privateKeyPath, $privateKeyPassword = null)
    {
        $this->certificatePath = $certificatePath;
        $this->privateKeyPath = $privateKeyPath;
        $this->privateKeyPassword = $privateKeyPassword;
    }

    public function sign($message)
    {
        $certContent = file_get_contents($this->certificatePath);
        $keyContent = file_get_contents($this->privateKeyPath);

        try {
            set_error_handler(function () {
                //throw SignException::canNotReadCertificate($this->certificatePath);
            });
            $cert = openssl_x509_read($certContent);
        } finally {
            restore_error_handler();
        }

        $privateKey = openssl_pkey_get_private($keyContent, $this->privateKeyPassword);
        if ($privateKey == null) {
            throw new ApiException(openssl_error_string(), 'ESIA_ERROR', null, 400);
        }

        $messageFile = tempnam(sys_get_temp_dir(), 'messageFile');
        $signFile = tempnam(sys_get_temp_dir(), 'signFile');
        file_put_contents($messageFile, $message);

        try {
            set_error_handler(function () {
            });
            $signResult = openssl_pkcs7_sign(
                $messageFile,
                $signFile,
                $cert,
                $privateKey,
                [],
                PKCS7_DETACHED | PKCS7_BINARY | PKCS7_NOATTR
            );
        } finally {
            restore_error_handler();
        }
        if (false === $signResult) {
            throw new ApiException(openssl_error_string(), 'ESIA_ERROR', null, 400);
        }

        $signed = file_get_contents($signFile);
        $signed = explode("\n\n", $signed);

        unlink($signFile);
        unlink($messageFile);

        return base64_decode($signed[3]);
    }
}
