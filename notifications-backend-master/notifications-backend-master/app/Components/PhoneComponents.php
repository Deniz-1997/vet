<?php


namespace App\Components;


use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class PhoneComponents
{

    /**
     * @var PhoneNumberUtil
     */
    private $phoneUtil;

    /**
     * PhoneComponents constructor.
     */
    public function __construct()
    {
        $this->phoneUtil = PhoneNumberUtil::getInstance();
    }

    /**
     * @param string $phone
     * @return string
     * @throws NumberParseException
     */
    public function parse(string $phone): string
    {
        $until = $this->phoneUtil->parse($phone, "RU");
        $code = $until->getCountryCode();
        $num = $until->getNationalNumber();
        return "+$code$num";
    }
}
