<?php

declare(strict_types=1);

namespace App\Service\UploadFile;

use App\Entity\UploadedFile as UploadedFileEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UploadFileService implements UploadFileServiceInterface
{
    private EntityManagerInterface $entityManager;
    private ContainerInterface $container;

    public function __construct(
        EntityManagerInterface $entityManager,
        ContainerInterface $container
    ) {
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    public function upload(UploadedFile $file): UploadedFileEntity
    {
        $md5 = md5_file($file->getPathname());

        $uploadedFile = $this->entityManager->getRepository(UploadedFileEntity::class)->findOneBy(
            ['md5' => $md5]
        );

        /** @var UploadedFileEntity $uploadedFile */
        if ($uploadedFile) {
            return $uploadedFile;
        }

        $array = str_split($md5, strlen($md5) / 4);
        $generatedFileName = $array[count($array) - 1] . '.' . $file->getClientOriginalExtension();
        unset($array[count($array) - 1]);
        $path = getenv('UPLOAD_FILE_PUBLIC_DIR').'/'.implode('/', $array);

        $uploadedFile = new UploadedFileEntity();
        $uploadedFile->setName($generatedFileName);
        $uploadedFile->setMimeType($file->getClientMimeType());
        $uploadedFile->setSize($file->getSize());
        $uploadedFile->setUploadedFile($file);
        $uploadedFile->setPath($path);
        $uploadedFile->setMd5($md5);

        $file->move($this->container->getParameter('kernel.root_dir').'/../public/'.$path, $generatedFileName);

        $this->entityManager->persist($uploadedFile);
        $this->entityManager->flush();

        return $uploadedFile;
    }
}