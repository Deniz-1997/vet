import { constructByInterface } from '@/utils/construct-by-interface';
import { QualityIndicatorsVueModel } from '@/models/Lot/QualityIndicators.vue';
import { applyMask } from '@/components/common/inputs/mask/decimalNumberMask';

export interface LotsMovedVueInterface {
  id?: number | null;
  lot_id?: number | null;
  gpb_id?: number | null;
  value: any;
  value_mask: string | null;
  lot_number: number | string | null;
  gpb_number: number | string | null;
  amount_kg_available: number | null;
  amount_kg_original?: number | string | null;
  target_id_moved?: number | null;
  okpd2_id: number | null;
  quality_indicators?: QualityIndicatorsVueModel[];
}

export class LotsMovedVueModel implements LotsMovedVueInterface {
  id!: number;
  lot_id!: number;
  gpb_id!: number;
  value!: any;
  value_mask!: string | null;
  lot_number!: number | string;
  gpb_number!: number | string;
  amount_kg_available!: number;
  amount_kg_original!: number | string;
  target_id_moved!: number;
  okpd2_id!: number | null;
  quality_indicators: QualityIndicatorsVueModel[] = [];

  constructor(o?: LotsMovedVueInterface) {
    constructByInterface(o, this, { quality_indicators: QualityIndicatorsVueModel });

    this.value_mask = applyMask(this.value, true);
  }
}
