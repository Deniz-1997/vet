<?php


namespace App\Tests\Helper;

use App\Helper\Import;
use PHPUnit\Framework\TestCase;

class ImportTest extends TestCase
{
    public function testMakeFloat()
    {
        $this->assertEquals(Import::makeFloat('1000'), 1000.00);
        $this->assertEquals(Import::makeFloat('2,644.00'), 2644.00);
        $this->assertEquals(Import::makeFloat('13.500'), 13.5);
        $this->assertEquals(Import::makeFloat('0.53'), 0.53);
        $this->assertEquals(Import::makeFloat('5.000'), 5.0);
        $this->assertEquals(Import::makeFloat(''), 0);
        $this->assertEquals(Import::makeFloat('3,400.000'), 3400.00);
        $this->assertEquals(Import::makeFloat('11,5'), 11.5);
        $this->assertEquals(Import::makeFloat('0.500'), 0.5);
        $this->assertEquals(Import::makeFloat('1 289.00'), 1289.00);
        $this->assertEquals(Import::makeFloat('1 340,00'), 1340.00);
    }
}
