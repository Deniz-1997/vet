<?php

namespace App\Interfaces\CAS;

interface CasApiAuthInterface
{
    public function getAuthToken(bool $refresh = false): ?string;
    public function refreshAuthToken(): ?string;
}
