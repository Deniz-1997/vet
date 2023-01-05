<?php

namespace App\Packages\Monolog;


class RecordProcessor
{

    /**
     * @param array $record
     * @return array
     */
    public function __invoke(array $record): array
    {
        if (isset($record['context']['tags'])) {
            $record['extra']['tags'] = $record['context']['tags'];
        }

        if (isset($record['context']['extra'])) {
            $record['extra'] = $record['context']['extra'];
            unset($record['context']['extra']);
        }

        return $record;
    }
}
