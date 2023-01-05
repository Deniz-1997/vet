import {constructByInterface} from '../../utils/construct-by-interface';

export interface ShopStateInterface {
  id: number;
  name: string;
}

export class ShopStateModel implements ShopStateInterface {
  id: number;
  name: string;

  constructor(o?: ShopStateInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}


