import {constructByInterface} from '../../utils/construct-by-interface';
import {ShopSettingsDataModel} from './shop.settings.data.models';

export interface ShopSettingsInterface {
  id: number;
  unit: ShopSettingsInterface;
  data: ShopSettingsDataModel;
  name: string;
  deleted: boolean;

}

export class ShopSettingsModel implements ShopSettingsInterface {
  id: number;
  unit: ShopSettingsModel;
  data: ShopSettingsDataModel;
  name: string;
  deleted: boolean;

  constructor(o?: ShopSettingsInterface) {
    constructByInterface(o, this);
  }
}
