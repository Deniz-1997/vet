<?php

namespace App\Packages\DTO\CashierEquipment;

use App\Packages\DBAL\Types\VatRateEnum;

class Tax
{
    /** @var string */
    public string $type;

    public function setTypeByVatRate(?string $vatRate): Tax
    {
        switch ($vatRate) {
            case VatRateEnum::NONE: // налогом не облагается
                $type = 'none';
                break;
            case VatRateEnum::VAT_0: // НДС 0%
                $type = 'vat0';
                break;
            case VatRateEnum::VAT_10: // НДС 10%
                $type = 'vat10';
                break;
            case VatRateEnum::VAT_18: // НДС 18%
                $type = 'vat18';
                break;
            case VatRateEnum::VAT_110: // НДС 10/110
                $type = 'vat110';
                break;
            case VatRateEnum::VAT_118: // НДС 18/118
                $type = 'vat118';
                break;
            case VatRateEnum::VAT_20: // НДС 20%
                $type = 'vat20';
                break;
            case VatRateEnum::VAT_120: // НДС 20/120
                $type = 'vat120';
                break;
            default:
                $type = null;
        }
        $this->type = $type;
        return $this;
    }
}
