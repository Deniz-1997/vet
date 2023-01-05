<?php


namespace App\Packages\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * tsvector datatype.
*/
class TsVector extends Type
{
    const TsVector = 'tsvector';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return 'TSVECTOR';
    }

    public function getName(): string
    {
        return self::TsVector; // modify to match your constant name
    }

    /**
     * {@inheritdoc}
     */
    public function canRequireSQLConversion(): bool
    {
        return true;
    }

    /**
     * Converts a value from its database representation to its PHP representation
     * of this type.
     *
     * @param mixed            $value    The value to convert.
     * @param AbstractPlatform $platform The currently used database platform.
     *
     * @return mixed The PHP representation of the value.
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): array
    {
        // Wish there was a database way to make this cleaner... implement in convertToPHPValueSQL
        $terms = array();
        if (!empty($value)) {
            foreach (explode(' ', $value) as $item) {
                list($term, $_) = explode(':', $item);
                $terms[] = trim($term, '\'');
            }
        }
        return $terms;
    }

    /**
     * Modifies the SQL expression (identifier, parameter) to convert to a PHP value.
     *
     * @param $sqlExpr
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function convertToPHPValueSQL($sqlExpr, $platform) : string
    {
        return $sqlExpr;
    }
    /**
     * Modifies the SQL expression (identifier, parameter) to convert to a database value.
     *
     * @param string           $sqlExpr
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform) : string
    {
        return sprintf('to_tsvector(%s)', $sqlExpr);
    }
    /**
     * Converts a value from its PHP representation to its database representation
     * of this type.
     *
     * @param mixed            $value    The value to convert.
     * @param AbstractPlatform $platform The currently used database platform.
     *
     * @return mixed The database representation of the value.
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (is_array($value)) {
            $value = implode(' ', $value);
        }
        return $value;
    }
}
