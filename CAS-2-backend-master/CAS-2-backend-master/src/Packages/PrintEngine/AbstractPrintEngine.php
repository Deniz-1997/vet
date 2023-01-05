<?php

namespace App\Packages\PrintEngine;

use App\Packages\DBAL\Types\Enum;
use App\Entity\PrintForm;
use App\Interfaces\PrintEngineInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AbstractPrintEngine
 */
abstract class AbstractPrintEngine implements PrintEngineInterface
{
    /** @var string */
    private string $rootDir;

    /** @var TranslatorInterface */
    private TranslatorInterface $translator;

    /**
     * @required
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param string $rootDir
     * @return $this
     */
    public function setRootDir(string $rootDir): self
    {
        $this->rootDir = $rootDir;

        return $this;
    }

    /**
     * @param string $content
     * @return array
     */
    protected function parseExpressions(string $content): array
    {
        preg_match_all('/(?<=\$\{)(.*?)(?=\})/im', $content, $matches, PREG_SET_ORDER);

        return array_map(function ($match) {
            return str_replace('&apos;', '\'', htmlspecialchars_decode($match[1]));
        }, $matches);
    }

    /**
     * @param $content
     * @param array $variables
     * @param boolean $escapeSpecialChars
     * @return array
     */
    protected function processExpressions($content, array $variables, $escapeSpecialChars = true): array
    {
        $pa = PropertyAccess::createPropertyAccessor();

        $result = [];
        $expressions = $this->parseExpressions($content);

        foreach ($expressions as $expression) {
            //Если это не функция - вызываем PropertyAccessor
            if (preg_match('/^[a-zA-Z._0-9\[\]]+$/', $expression)) {
                $expressionPaths = explode('.', $expression);
                $variable = $expressionPaths[0];
                unset($expressionPaths[0]);
                $propertyAccessorModifier = implode('.', $expressionPaths);

                if (isset($variables[$variable])) {
                    try {
                        $variableValue = $pa->getValue($variables[$variable], $propertyAccessorModifier);
                    } catch (\Exception $exception) {
                        $variableValue = null;
                    }

                    $result[$expression] = $variableValue;
                }
            } else {
                $key = $escapeSpecialChars ? str_replace('\'', '&apos;', $expression) : $expression;

                $handledValue = false;
                preg_match('/([a-z0-9A-Z.]+)\.[a-zA-Z0-9]+\(/', $expression, $matches);
                if (isset($matches[1]) && !isset($variables[$matches[1]])) {
                    $tmpVariableName = '_tmp_' . uniqid();
                    $variableModifierInfo = explode('.', $matches[1]);
                    if (isset($variableModifierInfo[0]) && count($variableModifierInfo) > 1 && isset($variables[$variableModifierInfo[0]])) {
                        $mainVariable = $variables[$variableModifierInfo[0]];
                        unset($variableModifierInfo[0]);
                        try {
                            $nestedPathModifier = implode('.', $variableModifierInfo);
                            $variables[$tmpVariableName] = $pa->getValue($mainVariable, $nestedPathModifier);
                            $expression = str_replace($matches[1], $tmpVariableName, $expression);
                        } catch (\Exception $exception) {
                            $handledValue = '';
                        }
                    }
                }
                try {
                    $result[$key] = $handledValue !== false ? $handledValue : $this->createExpressionLanguage()->evaluate($expression, $variables);
                } catch (\RuntimeException $exception) {
                    $result[$key] = null;
                }
            }
        }

        foreach ($result as $k => $value) {
            if ($value instanceof Enum) {
                $result[$k] = $this->translator->trans($value::getLabelCode($value->code), [], 'enum');
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    private function getPublicDirectory(): string
    {
        return $this->rootDir . '/../public/' . getenv('UPLOAD_FILE_PUBLIC_DIR');
    }

    /**
     * @param PrintForm $printForm
     * @return string
     */
    protected function getTemplateFileDestination(PrintForm $printForm): string
    {
        return $this->getPublicDirectory() . '/' . $printForm->getFile()->getName();
    }

    /**
     * @param PrintForm $printForm
     * @return string
     */
    protected function generatePrintFormNameFile(PrintForm $printForm): string
    {
        $fileName = $printForm->getFile()->getName();

        $fileExtension = explode('.', $fileName)[1];

        return $this->getPublicDirectory() . '/' . md5($fileName) . '.' . $fileExtension;
    }

    /**
     * @return ExpressionLanguage
     */
    protected function createExpressionLanguage(): ExpressionLanguage
    {
        $expressionLanguage = new ExpressionLanguage();

        $expressionLanguage->register('getValue', function ($data) {
            return sprintf('getValue($data, $defaultData)');
        }, (function ($arguments, $str, $defaultValue) {
            try {
                if ($result = array_values($this->processExpressions('${' . $str . '}', $arguments))) {
                    return isset($result[0]) ? $result[0] : $defaultValue;
                } else {
                    return $defaultValue;
                }
            } catch (\Exception $exception) {
                return $defaultValue;
            }
        })->bindTo($this));

        return $expressionLanguage;
    }

    /**
     * @param string $templateContent
     * @param array $variables
     * @param array $delimiters
     * @return string
     */
    protected function processScript(string $templateContent, array $variables, $delimiters = ['${', '}']): string
    {
        $variables = $this->processExpressions($templateContent, $variables, false);

        foreach ($variables as $variableName => $variableValue) {
            $templateVariable = $delimiters[0] . $variableName . $delimiters[1];
            $templateContent = str_replace($templateVariable, $variableValue, $templateContent);
        }

        return $templateContent;
    }

    /**
     * @param PrintForm $printForm
     * @return bool
     */
    abstract public function support(PrintForm $printForm): bool;

    /**
     * @param PrintForm $printForm
     * @param array $variables
     * @return string
     */
    abstract public function processTemplate(PrintForm $printForm, array $variables): string;
}
