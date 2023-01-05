<?php

namespace App\Interfaces\ESIA;

interface ESIAInterface
{
    public function GetAuthorizationUrl();
    public function AuthorizeUser(string $authorizeCode);
}
