<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UploadedFileRepository")
 */
class UploadedFile
{
    use OrmIdTrait, OrmNameTrait;

    const TYPE_PRINT_FORM = 'print_form';
    const TYPE_DEFAULT = 'default';

    /**
     * @var string
     * @ORM\Column(type="string", options={"default": UploadedFile::TYPE_DEFAULT})
     */
    private $type = UploadedFile::TYPE_DEFAULT;

    /**
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile|null
     *
     * @Groups({"expose"})
     *
     * @Assert\File(
     *     mimeTypes={
     *         "image/png",
     *         "image/x-png",
     *         "image/jpeg",
     *         "image/x-citrix-jpeg",
     *         "image/gif",
     *         "image/bmp",
     *         "application/pdf",
     *         "application/x-pdf",
     *         "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
     *         "application/msword",
     *         "application/octet-stream",
     *         "application/rtf",
     *         "application/vnd.ms-excel",
     *         "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
     *         "text/plain",
     *         "text/csv",
     *         "text/html",
     *         "application/zip",
     *         "application/x-rar-compressed",
     *         "application/x-7z-compressed",
     *         "application/x-tar"
     *     },
     *     mimeTypesMessage="uploaded_file.mime_types_error"
     * )
     */
    private $uploadedFile;

    /**
     * @var string тип
     *
     * @Groups({"default"})
     *
     * @ORM\Column(type="string")
     */
    private $mimeType;

    /**
     * @var integer вес файла
     *
     * @Groups({"default"})
     *
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @var string
     * @Groups({"default"})
     */
    private $relativePath;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $path;

    /**
     * @var string
     * @Groups({"default"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $md5;

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param string $mimeType
     */
    public function setMimeType(string $mimeType)
    {
        $this->mimeType = $mimeType;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size)
    {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getRelativePath()
    {
        return $this->relativePath;
    }

    /**
     * @param string $relativePath
     */
    public function setRelativePath(string $relativePath)
    {
        $this->relativePath = $relativePath;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getUploadedFile(): ?\Symfony\Component\HttpFoundation\File\UploadedFile
    {
        return $this->uploadedFile;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     * @return $this
     */
    public function setUploadedFile(\Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile): self
    {
        $this->uploadedFile = $uploadedFile;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getMd5(): ?string
    {
        return $this->md5;
    }

    public function setMd5(string $md5): self
    {
        $this->md5 = $md5;

        return $this;
    }
}
