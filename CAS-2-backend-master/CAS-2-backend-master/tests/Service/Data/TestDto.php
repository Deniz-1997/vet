<?php

namespace App\Tests\Service\Data;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TestDto
 */
class TestDto
{
    /**
     * @var string
     * @Assert\Length(min="2", max="15")
     */
    public $name;
    /**
     * @var int
     * @Assert\Type(type="int")
     */
    public $counter;
}
