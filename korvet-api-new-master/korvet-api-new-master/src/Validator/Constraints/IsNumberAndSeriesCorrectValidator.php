<?php

namespace App\Validator\Constraints;

use App\Entity\Owner\Embeddable\Passport;
use App\Enum\DocumentTypeEnum;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsNumberAndSeriesCorrectValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        /** @var Passport $value */

        $series = $value->getSeries();
        $number = $value->getNumber();
        switch ($value->getDocumentType()) {
            case DocumentTypeEnum::USSR_CITIZEN_PASSPORT:
                $seriesPattern = "/^(C{0,3}(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})|С{0,3}(ХС|ХЛ|Л?Х{0,3})(1Х|1У|У?1{0,3}))[-]{1}[А-ЯЁ]{2}$/u";
                $numberPattern = "/^\d{6}$/";
                break;
            case DocumentTypeEnum::USSR_CITIZEN_INTERNATIONAL_PASSPORT:
                $seriesPattern = "/^\d{2}$/";
                $numberPattern = "/^\d{6,7}$/";
                break;
            case DocumentTypeEnum::OFFICER_IDENTITY_CARD:
            case DocumentTypeEnum::MILITARY_ID:
            case DocumentTypeEnum::SAILOR_PASSPORT:
            case DocumentTypeEnum::STOCK_OFFICER_MILITARY_CARD:
                $seriesPattern = "/^[А-ЯЁ]{2}$/u";
                $numberPattern = "/^\d{6,7}$/";
                break;
            case DocumentTypeEnum::NAVY_MINISTRY_PASSPORT:
                $seriesPattern = "/^[А-ЯЁ]{2}$/u";
                $numberPattern = "/^\d{6}$/";
                break;
            case DocumentTypeEnum::DIPLOMATIC_PASSPORT:
            case DocumentTypeEnum::RF_CITIZEN_INTERNATIONAL_PASSPORT:
                $seriesPattern = "/^\d{2}$/";
                $numberPattern = "/^\d{7}$/";
                break;
            case DocumentTypeEnum::TEMPORARY_ASYLUM_CERTIFICATE:
                $seriesPattern = "/^[А-ЯЁ]{2}[-]{1}\d{3}$/u";
                $numberPattern = "/^\d{7}$/";
                break;
            case DocumentTypeEnum::RF_CITIZEN_PASSPORT:
                $seriesPattern = "/^\d{2}\s{1,5}\d{2}$/";
                $numberPattern = "/^\d{6,7}$/";
                break;
            default:
                return;
        }

        if (! preg_match($seriesPattern, $series) || ! preg_match($numberPattern, $number)) {
            $this->context
                ->buildViolation('error.validation.passport')
                ->addViolation();
        }
    }
}
