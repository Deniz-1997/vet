import { constructByInterface } from '@/utils/construct-by-interface';

export interface SdizExtinguishCreateVueInterface {
  sdiz_id?: number | null;
  gpb_sdiz_id?: number | null;
  amount_kg: number;
  full_use: boolean;
  create_gpbo: boolean;
  note?: string | null;
  transports_ids: number[];
  reason_id?: number | null;
}

export class SdizExtinguishCreateVueModel implements SdizExtinguishCreateVueInterface {
  sdiz_id?: number | null;
  gpb_sdiz_id?: number | null;
  amount_kg = 0;
  full_use = false;
  create_gpbo = false;
  note?: string | null;
  transports_ids: number[] = [];
  reason_id?: number | null;

  constructor(o?) {
    if (o) {
      constructByInterface(o, this, {}, true);
    }
  }
}
