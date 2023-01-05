import {constructByInterface} from '../../utils/construct-by-interface';

export interface ShopSettingDataInterface {
  stock: {id: number, name: string};
}

export class ShopSettingsDataModel implements ShopSettingDataInterface {
  stock: {id: number , name: string};
  constructor(o?: ShopSettingDataInterface) {
    constructByInterface(o, this);
  }
}
