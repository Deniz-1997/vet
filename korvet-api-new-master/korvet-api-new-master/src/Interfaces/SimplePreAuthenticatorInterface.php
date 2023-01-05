<?php


namespace App\Interfaces;

use Symfony\Component\HttpFoundation\Request;


interface SimplePreAuthenticatorInterface extends SimpleAuthenticatorInterface
{
    public function createToken(Request $request, $providerKey);
}
