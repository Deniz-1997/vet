import { constructByInterface } from '@/utils/construct-by-interface';

export interface CarrierVueInterface {
  subject_id: number | null;
  inn: number | null;
  kpp: number | null;
  name: string | null;
  address_text: string | null;
  registration_number: string | null;
}

export class CarrierVueModel implements CarrierVueInterface {
  inn: number | null = null;
  kpp: number | null = null;
  name: string | null = '-';
  address_text: string | null = null;
  registration_number: string | null = null;
  subject_id: number | null = null;

  constructor(o?) {
    constructByInterface(o, this);
  }
}
