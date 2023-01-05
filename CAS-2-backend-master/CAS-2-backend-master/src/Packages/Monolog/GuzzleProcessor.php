<?php
/**
 * Created by PhpStorm.
 * User: anboo
 * Date: 01.10.18
 * Time: 2:29
 */

namespace App\Packages\Monolog;

/**
 * Class GuzzleProcessor
 */
class GuzzleProcessor
{

    /**
     * @param array $record
     * @return array
     */
    public function __invoke(array $record): array
    {
        $array = json_decode($record['message'], true);

        if (!is_array($array) || !isset($array['guzzle'])) {
            return $record;
        }

        $record['message'] = $array['message'];
        unset($array['message']);
        $record['extra'] = $array;

        return $record;
    }
}
