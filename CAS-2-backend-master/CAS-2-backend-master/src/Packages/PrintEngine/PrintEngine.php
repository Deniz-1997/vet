<?php

namespace App\Packages\PrintEngine;

use App\Entity\PrintForm;
use App\Interfaces\PrintEngineInterface;

/**
 * Class PrintEngine
 */
class PrintEngine
{
    /** @var PrintEngineInterface[] */
    private $printEngines;

    /**
     * PrintEngine constructor.
     * @param PrintEngineInterface[] $printEngines
     */
    public function __construct(iterable $printEngines)
    {
        $this->printEngines = $printEngines;
    }

    /**
     * @param PrintForm $printForm
     * @param array $variables
     * @return string|null
     */
    public function processPrintForm(PrintForm $printForm, array $variables) : ?string
    {
        foreach ($this->printEngines as $printEngine) {
            if ($printEngine->support($printForm)) {
                return $printEngine->processTemplate($printForm, $variables);
            }
        }

        return null;
    }
}
