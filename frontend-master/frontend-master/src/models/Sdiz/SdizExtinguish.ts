import { constructByInterface } from '@/utils/construct-by-interface';
import { DocsTransportsVueModel } from '@/models/Sdiz/DocsTransports.vue';
import { numberThousandsMask } from '@/components/common/inputs/mask/numberThousandsMask';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';

export interface SdizExtinguishVueInterface {
  id: number;
  full_use: boolean;
  create_gpbo: boolean;
  lot_number: string;
  sdiz_number: string;
  amount_note: string;
  operation_date: number;
  create_lot_id: number | null;
  created_by_id: number;
  is_canceled: boolean;

  amount_kg: number;
  amount_kg_mask: string;

  reason_id: number | null;

  transports: DocsTransportsVueModel[];

  lot: LotDataVueModel;
  gpb: LotGpbDataVueModel;
}

export class SdizExtinguishVueModel implements SdizExtinguishVueInterface {
  id!: number;
  full_use!: boolean;
  create_gpbo: boolean = false;
  lot_number: string = '-';
  sdiz_number: string = '-';
  amount_note: string = '-';

  is_canceled: boolean = false;
  create_lot_id: number | null = null;
  created_by_id: number = 0;

  operation_date: number = 0;

  amount_kg: number = 0;
  amount_kg_mask: string = '';

  reason_id: number | null = null;

  lot: LotDataVueModel = new LotDataVueModel();
  gpb: LotGpbDataVueModel = new LotGpbDataVueModel();
  transports: DocsTransportsVueModel[] = [];

  constructor(o?: SdizExtinguishVueInterface) {
    if (o !== undefined) {
      constructByInterface(o, this, { gpb: LotGpbDataVueModel, lot: LotDataVueModel });
      this.amount_kg_mask = numberThousandsMask(this.amount_kg)[0];
    }
  }
}
