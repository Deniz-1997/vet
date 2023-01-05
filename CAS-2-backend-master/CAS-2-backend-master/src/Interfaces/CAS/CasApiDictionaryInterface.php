<?php

namespace App\Interfaces\CAS;

interface CasApiDictionaryInterface
{
    public function getDictionary(bool $refresh = false): ?string;
}
