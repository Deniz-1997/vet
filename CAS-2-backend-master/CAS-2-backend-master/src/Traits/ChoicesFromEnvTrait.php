<?php

namespace App\Traits;

/**
 *
 * Ограничение вывода значений ENUM
 *
 * Trait ChoicesFromEnvTrait
 */
trait ChoicesFromEnvTrait
{
    /**
     * Как использовать:
     * 1) Определить константу ENV_VAR в целевом классе Enum. В ней должно содержаться название переменной из .env,
     *       в которой через запятую указаны значения для вывода.
     * 2) NULLABLE_DISABLED используется для исключения null значения.
     *
     * @example Добавить в .env PRODUCT_CODE_TYPE_ENUM="FURS, SHOES".
     *      В целевом классе создать константу ENV_VAR='PRODUCT_CODE_TYPE_ENUM'
     *
     * @return array
     */
    public static function choices(): array
    {
        $envEnum = getenv(self::ENV_VAR);
        $choices = self::$choices;

        if ($envEnum) {
            $envChoices = explode(',', $envEnum);

            if (is_array($envChoices)) {
                $choices = array_intersect_key(
                    self::$choices,
                    array_flip(array_map('trim', $envChoices))
                );
            }
        }

        if (!defined('self::NULLABLE_DISABLED') || self::NULLABLE_DISABLED === false) {
            $nullableChoice = [self::NULLABLE => 'enum.nullable.value'];
            $choices = array_merge_recursive($nullableChoice, $choices);
        }

        return $choices;
    }
}
