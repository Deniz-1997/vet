<?php

namespace App\Interfaces\CAS;

use App\Entity\User\User;

interface CasAuthInterface
{
    public function loadCasUser(string $username, string $password): ?User;
}
