<?php

namespace App\Interfaces;

use App\Entity\PrintForm;
use App\Entity\UploadedFile;

/**
 * Interface PrintEngineInterface
 */
interface PrintEngineInterface
{
    public function support(PrintForm $printForm) : bool;
    public function processTemplate(PrintForm $printForm, array $variables) : string;
}
