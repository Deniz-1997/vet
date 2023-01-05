<?php

namespace App\Packages\Normalizer;

use Acelaya\Doctrine\Type\PhpEnumType;
use App\Exception\ApiException;
use App\Interfaces\EnumInterface;
use App\Traits\CreateExceptionTranslationTrait;
use Doctrine\DBAL\DBALException;
use MyCLabs\Enum\Enum;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use \App\Packages\DBAL\Types\Enum as BaseEnum;
use function is_subclass_of;

/**
 * Class EnumDenormalizer
 */
class EnumDenormalizer implements DenormalizerInterface
{
    use CreateExceptionTranslationTrait;

    /**
     * @var TranslatorInterface
     */
    protected TranslatorInterface $translator;

    /**
     * EnumDenormalizer constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param mixed  $data
     * @param string $type
     * @param null   $format
     * @param array  $context
     *
     * @return object|BaseEnum
     * @throws ReflectionException
     * @throws ApiException|DBALException
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        /** @var BaseEnum|Enum $enum */
        $enum = is_subclass_of($type, PhpEnumType::class)? clone $type::getType($type) : new $type($data);

        $allowValues = implode(', ', array_keys($enum::choices()));
        if (!is_array($data)) {
            if (!$enum::hasCode(trim($data))) {
                $dataTranslator = [
                    '{{value}}' => $data,
                    '{{allow_values}}' => $allowValues,
                ];
                $this->createErrorValidation($type, $dataTranslator);
            }
            $enum->code = $data;
        } else {
            if (isset($data['code'])) {
                if ($data['code'] === null) {
                    $data['code'] = 'NULL';
                }
                if (!$enum::hasCode(trim($data['code']))) {
                    $dataTranslator = [
                        '{{value}}' => $data['code'],
                        '{{allow_values}}' => $allowValues,
                    ];
                    $this->createErrorValidation($type, $dataTranslator);
                }
                $enum->code = $data['code'];
            }
        }
        $enum->title = $this->translator->trans($enum::getLabelCode($enum->code), [], 'enum');

        return $enum;
    }

    /**
     * @param mixed  $data
     * @param string $type
     * @param null   $format
     *
     * @return bool
     */
    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return is_subclass_of($type, EnumInterface::class) || is_subclass_of($type, PhpEnumType::class);
    }

    /**
     * @param string $class
     * @param array $dataTranslator
     *
     * @throws ReflectionException
     * @throws ApiException
     */
    private function createErrorValidation(string $class, array $dataTranslator): void
    {
        $this->createException('error.enum_item.not_found', [], Response::HTTP_BAD_REQUEST, $dataTranslator, (new ReflectionClass($class))->getShortName() . '.code');
    }
}
