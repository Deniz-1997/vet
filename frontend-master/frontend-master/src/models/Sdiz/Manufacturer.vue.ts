import { constructByInterface } from '@/utils/construct-by-interface';

export interface ManufacturerVueInterface {
  inn: number | null;
  kpp: number | null;
  name: string | null;
  short_name: string | null;
  location: string | null;
  repository: string | null;
  address_text: string | null;
}

export class ManufacturerVueModel implements ManufacturerVueInterface {
  inn: number | null = null;
  kpp: number | null = null;
  name: string | null = '-';
  short_name: string | null = '';
  location: string | null = null;
  repository: string | null = null;
  address_text: string | null = null;

  constructor(o?) {
    constructByInterface(o, this);
  }
}
