import {constructByInterface} from '../../utils/construct-by-interface';

export interface ReferenceUnitInterface {
  id?: number;
  name: string;
  address: string;
  coordinates: string;
  is_around_clock: boolean;
  deleted: boolean;
  without_registry: boolean;
  full_name: string;
  phone: string;
  email: string;
  website_url: string;
}

export class ReferenceUnitModel implements ReferenceUnitInterface {
  id: number;
  name: string;
  address: string;
  coordinates: string;
  is_around_clock: boolean;
  deleted: boolean;
  without_registry = false;
  full_name: string;
  phone: string;
  email: string;
  website_url: string;

  constructor(o?: ReferenceUnitInterface) {
    constructByInterface(o, this);
  }
}
