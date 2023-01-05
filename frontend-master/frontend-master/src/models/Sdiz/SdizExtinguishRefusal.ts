import { constructByInterface } from '@/utils/construct-by-interface';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';

export interface SdizExtinguishRefusalInterface {
  id: number;
  create_lot_id: number | null;
  sdiz_id: number | null;
  operator_id: number | null;
  reason_id: number | null;
  amount_kg: number;
  comment: string;
  date: number | null;
  esp_id: number | null;
  canceled_esp_id: number | null;
  is_canceled: boolean;
  create_lot_number: string | null;

  lot: LotDataVueModel;
  gpb: LotGpbDataVueModel;
}

export class SdizExtinguishRefusalModel implements SdizExtinguishRefusalInterface {
  id!: number;
  create_lot_id: number | null = null;
  sdiz_id: number | null = null;
  operator_id: number | null = null;
  reason_id: number | null = null;
  amount_kg = 0;
  comment = '';
  date: number | null = null;
  esp_id: number | null = null;
  canceled_esp_id: number | null = null;
  is_canceled = false;
  create_lot_number: string | null = null;

  lot: LotDataVueModel = new LotDataVueModel();
  gpb: LotGpbDataVueModel = new LotGpbDataVueModel();

  constructor(o?: SdizExtinguishRefusalInterface) {
    if (o !== undefined) {
      constructByInterface(o, this, { gpb: LotGpbDataVueModel, lot: LotDataVueModel });
    }
  }
}
