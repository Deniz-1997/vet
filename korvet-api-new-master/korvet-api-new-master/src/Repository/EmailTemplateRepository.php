<?php

namespace App\Repository;


use App\Entity\Template;

class EmailTemplateRepository extends ResourceRepository
{
    /**
     * @param int $templateId
     *
     * @return null|Template
     */
    public function getNotDeletedTemplate(int $templateId): ?Template
    {
        return $this->findOneBy([
            'id' => $templateId,
            'deleted' => false,
        ]);
    }

    /**
     * @param string $file
     *
     * @return null|Template
     */
    public function getByFile(string $file): ?Template
    {
        return $this->findOneBy([
            'file' => $file,
        ]);
    }
}
