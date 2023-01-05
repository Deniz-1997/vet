<?php


namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;

/**
 * Class LegalFormsEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class LegalFormsEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = LegalFormsEnum::class;

    public const IP = 'IP';
    public const OOO = 'OOO';
    public const AO = 'AO';
    public const PAO = 'PAO';
    public const NKO = 'NKO';
    public const OP = 'OP';
    public const ZAO = 'ZAO';


    /**
     * @return array Перевод значений на язык системы
     */
    protected static $choices = [
        self::IP => 'enum.legal_forms.ip',
        self::OOO => 'enum.legal_forms.ooo',
        self::AO => 'enum.legal_forms.ao',
        self::PAO => 'enum.legal_forms.pao',
        self::NKO => 'enum.legal_forms.nko',
        self::OP => 'enum.legal_forms.op',
        self::ZAO => 'enum.legal_forms.zao',
    ];

}
