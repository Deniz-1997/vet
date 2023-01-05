<?php

namespace App\Repository;


use App\Entity\Theme;

class EmailThemeRepository extends ResourceRepository
{
    /**
     * @param int $themeId
     *
     * @return null|Theme
     */
    public function getNotDeletedTheme(int $themeId): ?Theme
    {
        return $this->findOneBy([
            'id' => $themeId,
            'deleted' => false,
        ]);
    }

    /**
     * @param string $file
     *
     * @return null|Theme
     */
    public function getByFile(string $file): ?Theme
    {
        return $this->findOneBy([
            'file' => $file,
        ]);
    }

    public function getDefaultTheme(): ?Theme
    {
        return $this->findOneBy([
            'isDefault' => true
        ]);
    }
}
