<?php

namespace App\Packages\PrintEngine;

use App\Entity\PrintForm;

/**
 * Class HtmlPrintEngine
 */
class HtmlPrintEngine extends AbstractPrintEngine
{
    /**
     * @param PrintForm $printForm
     * @return bool
     */
    public function support(PrintForm $printForm): bool
    {
        return $printForm->getFile()->getMimeType() === 'text/html';
    }

    /**
     * @param PrintForm $printForm
     * @param array $variables
     * @return string
     */
    public function processTemplate(PrintForm $printForm, array $variables): string
    {
        $templateFileDestination = $this->getTemplateFileDestination($printForm);
        $templateContent = file_get_contents($templateFileDestination);

        $variables = $this->processExpressions($templateContent, $variables, false);
        foreach ($variables as $variableName => $variableValue) {
            $templateVariable = '${'.$variableName.'}';
            $templateContent = str_replace($templateVariable, $variableValue, $templateContent);
        }

        return $templateContent;
    }
}
