import { constructByInterface } from '@/utils/construct-by-interface';
import { applyMask } from '@/components/common/inputs/mask/decimalNumberMask';

export interface SdizDataInterface {
  lot_sp_number: string | number | null;
  amount_kg: number | null;
  amount_kg_mask: string | null;
  items: Array<any>;
}

export class SdizDataVueModel implements SdizDataInterface {
  lot_sp_number!: string | number;
  amount_kg!: number;
  amount_kg_mask!: string | null;
  items: Array<any> = [];

  constructor(o?: SdizDataInterface) {
    if (o !== undefined) {
      o.amount_kg_mask = applyMask(o.amount_kg, true);
      constructByInterface(o, this);
      this.items.push(o);
    }
  }
}
