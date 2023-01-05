<?php

declare(strict_types=1);

namespace App\Service\UploadFile;

use App\Entity\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as RequestUploadedFile;

interface UploadFileServiceInterface
{
    public function upload(RequestUploadedFile $file): UploadedFile;
}