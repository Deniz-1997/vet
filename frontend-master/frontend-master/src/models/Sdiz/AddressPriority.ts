import { constructByInterface } from '@/utils/construct-by-interface';

export interface CarrierLocationInterface {
  location_id: number | null;
  additional_info: string | null;
  postcode: string | null;
  address: string | null;
}

export class CarrierLocationModel implements CarrierLocationInterface {
  location_id: number | null = null;
  additional_info: string | null = null;
  postcode: string | null = null;
  address: string | null = null;

  constructor(o?: CarrierLocationInterface) {
    constructByInterface(o, this);
  }
}
