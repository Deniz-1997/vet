<?php

namespace App\Traits;

use App\Exceptions\ValidationException;
use Illuminate\Support\MessageBag;
use Validator;

/**
 * Trait ValidatorComponentTrait
 * @package App\Traits
 */
trait TraitValidator
{
    /**
     * Правила валидации для добавление записи
     *
     * @var array $rules
     */
    protected static $rules = [];

    /**
     * @var array $_arrayValidator
     */
    protected static $arrayValidator = [];

    /**
     * @var \Illuminate\Validation\Validator $_validator
     */
    protected static $_validator;

    /**
     * @return \Illuminate\Validation\Validator
     * @throws ValidationException
     */
    private static function _validator(): \Illuminate\Validation\Validator
    {
        if (count(self::$arrayValidator) === 0) {
            throw new ValidationException('Add data');
        }

        if (count(static::$rules) === 0) {
            throw new ValidationException('Add rules');
        }

        self::$_validator = Validator::make(self::$arrayValidator, static::$rules);

        return self::$_validator;
    }

    /**
     * @param array $array
     * @throws ValidationException
     */
    public static function validation(array $array): void
    {
        self::$arrayValidator = $array;

        if (self::_validator()->fails()) {
            throw new ValidationException(self::validatorMessages());
        }
    }

    /**
     * @return MessageBag
     * @throws ValidationException
     */
    public static function validatorMessages(): MessageBag
    {
        return self::_validator()->messages();
    }
}
