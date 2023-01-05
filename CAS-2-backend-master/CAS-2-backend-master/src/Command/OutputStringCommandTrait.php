<?php

namespace App\Command;

/**
 * Trait OutputStringCommandTrait
 */
trait OutputStringCommandTrait
{
    protected function GetOutputString(int $total, int $current)
    {
        $percent = intval($current * 100 / $total);
        $totalstring = $percent . '% :';
        for ($i = 0; $i < $percent; $i++) {
            $totalstring .= '=';
        }
        return $totalstring .= '>';
    }
}
