import { constructByInterface } from '@/utils/construct-by-interface';

export interface SdizExtinguishCreateVueInterface {
  sdiz_id: number;
  amount_kg: number;
  operation_type: number;
  operator_id: number;
  owner_id: number;
  full_use: boolean;
  is_gpb_out: boolean;
  amount_transport_id: number[];
  amount_reason_id?: number | null;
  amount_note?: string | null;
}

export interface SdizExtinguishGpbCreateVueInterface {
  sdiz_id: number;
  amount_kg: number;
  operation_type: number;
  operator_id: number;
  full_use: boolean;
  is_gpb_out: boolean;
  amount_transport_id: number[];
  amount_reason_id?: number | null;
  amount_note?: string | null;
}

export class SdizExtinguishCreateVueModel implements SdizExtinguishCreateVueInterface {
  sdiz_id = 0;
  amount_kg = 0;
  operation_type = 0;
  operator_id = 0;
  owner_id = 0;
  full_use = false;
  is_gpb_out = false;
  amount_transport_id: number[] = [];
  amount_reason_id?: number | null;
  amount_note?: string | null;

  constructor(o?) {
    if (o) {
      constructByInterface(o, this, {}, true);
    }
  }
}

export class SdizGbpExtinguishCreateVueModel implements SdizExtinguishGpbCreateVueInterface {
  sdiz_id = 0;
  amount_kg = 0;
  operation_type = 0;
  operator_id = 0;
  full_use = false;
  is_gpb_out = false;
  amount_transport_id: number[] = [];
  amount_reason_id?: number | null;
  amount_note?: string | null;

  constructor(o?) {
    if (o) {
      constructByInterface(o, this, {}, true);
    }
  }
}
