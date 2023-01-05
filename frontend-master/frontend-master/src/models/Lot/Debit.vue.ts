import { constructByInterface } from '@/utils/construct-by-interface';
import { applyMask } from '@/components/common/inputs/mask/decimalNumberMask';

export interface DebitVueInterface {
  id: number;
  amount_kg_debit: number;
  amount_kg_debit_mask: string;
  note: string;
  reason_id: number;
}

export class DebitVueModel implements DebitVueInterface {
  id!: number;
  amount_kg_debit!: number;
  amount_kg_debit_mask!: string;
  note!: string;
  reason_id!: number;
  constructor(o?) {
    if (o !== undefined) {
      constructByInterface(o, this);

      if (this.amount_kg_debit !== null) {
        this.amount_kg_debit_mask = applyMask(this.amount_kg_debit, true);
      }
    }
  }
}
