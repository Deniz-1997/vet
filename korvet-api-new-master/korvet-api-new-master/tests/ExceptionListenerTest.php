<?php

namespace App\Tests;

use App\Tests\Base\BaseCrudTestCase;

/**
 * Class ExceptionListenerTest
 */
class ExceptionListenerTest extends BaseCrudTestCase
{
    public static $actions = [
        'pickingStart',
    ];
    public $isGateway = true;
}
