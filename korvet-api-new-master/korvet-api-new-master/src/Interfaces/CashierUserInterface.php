<?php

namespace App\Interfaces;

/**
 * Class User
 */
interface CashierUserInterface
{
    public function getId();

    public function getName();

    public function getSurname();

    public function getPatronymic();

    public function getInn();
}
