<?php

namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;
use App\Traits\ChoicesFromEnvTrait;

/**
 * Class PaymentObjectEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class PaymentObjectEnum extends Enum
{
    use ChoicesFromEnvTrait;

    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = PaymentObjectEnum::class;
    private const ENV_VAR = 'PAYMENT_OBJECT_ENUM';

    public const COMMODITY = 'COMMODITY';
    public const EXCISE = 'EXCISE';
    public const JOB = 'JOB';
    public const SERVICE = 'SERVICE';
    public const GAMBLING_BET = 'GAMBLING_BET';
    public const GAMBLING_PRIZE = 'GAMBLING_PRIZE';
    public const LOTTERY = 'LOTTERY';
    public const LOTTERY_PRIZE = 'LOTTERY_PRIZE';
    public const INTELLECTUAL_ACTIVITY = 'INTELLECTUAL_ACTIVITY';
    public const PAYMENT = 'PAYMENT';
    public const AGENT_COMMISSION = 'AGENT_COMMISSION';
    public const PROPRIETARY_LAW = 'PROPRIETARY_LAW';
    public const NON_OPERATING_INCOME = 'NON_OPERATING_INCOME';
    public const INSURANCE_CONTRIBUTIONS = 'INSURANCE_CONTRIBUTIONS';
    public const MERCHANT_TAX = 'MERCHANT_TAX';
    public const RESORT_FEE = 'RESORT_FEE';
    public const DEPOSIT = 'DEPOSIT';
    public const COMPOSITE = 'COMPOSITE';
    public const ANOTHER = 'ANOTHER';
    public const MEDICINES = 'MEDICINES';
    public const CONSUMABLES = 'CONSUMABLES';

    /**
     * @var array
     */
    protected static $choices = [
        self::COMMODITY => 'enum.payment_object.commodity',
        self::EXCISE => 'enum.payment_object.excise',
        self::JOB => 'enum.payment_object.job',
        self::SERVICE => 'enum.payment_object.service',
        self::GAMBLING_BET => 'enum.payment_object.gambling_bet',
        self::GAMBLING_PRIZE => 'enum.payment_object.gambling_prize',
        self::LOTTERY => 'enum.payment_object.lottery',
        self::LOTTERY_PRIZE => 'enum.payment_object.lottery_prize',
        self::INTELLECTUAL_ACTIVITY => 'enum.payment_object.intellectual_activity',
        self::PAYMENT => 'enum.payment_object.payment',
        self::AGENT_COMMISSION => 'enum.payment_object.agent_commission',
        self::PROPRIETARY_LAW => 'enum.payment_object.proprietary_law',
        self::NON_OPERATING_INCOME => 'enum.payment_object.non_operating_income',
        self::INSURANCE_CONTRIBUTIONS => 'enum.payment_object.insuranse_contribution',
        self::MERCHANT_TAX => 'enum.payment_object.merchant_tax',
        self::RESORT_FEE => 'enum.payment_object.resort_fee',
        self::DEPOSIT => 'enum.payment_object.deposit',
        self::COMPOSITE => 'enum.payment_object.composite',
        self::ANOTHER => 'enum.payment_object.another',
        self::MEDICINES => 'enum.payment_object.medicines',
        self::CONSUMABLES => 'enum.payment_object.consumables',
    ];
}
