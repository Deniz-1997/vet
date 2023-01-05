<?php

namespace App\Tests\Controller;

use App\Service\ValidationService;
use Nelmio\ApiDocBundle\Tests\Functional\WebTestCase;

class ValidationServiceTest extends WebTestCase
{
    /**
     * @var ValidationService
     */
    private $validatorService;

    public function setUp(): void
    {
        parent::setUp();
        $this->validatorService = $this->createClient()->getContainer()->get('App\\Service\ValidationService');
    }

    public function testValidDto()
    {
        $dto = new TestDto();
        $dto->name = 0;
        $dto->counter = 'string';
        $violations = $this->validatorService->validate($dto, null, null, false);

        self::assertTrue(count($violations) == 2);
    }
}
