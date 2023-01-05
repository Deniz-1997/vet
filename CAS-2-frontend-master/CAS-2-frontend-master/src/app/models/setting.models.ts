import {constructByInterface} from '../utils/construct-by-interface';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../modules/shared/decorators/property-type.decorator';

export interface SettingInterface {
  key: string;
  value: string;
  id?: number;
}

export class SettingModel implements SettingInterface {
  @propertyesType({type: PropertyViewType.INPUT_STRING,  title: 'Ключ', col: 12, required: true})
  key: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING,  title: 'Значение', col: 6, required: true, objectType: PropertyViewObjectType.ANY})
  value: string;
  @propertyesType({type: PropertyViewType.INPUT_INT,  title: 'Сортировка', col: 6, required: false })
  sort: string;
  id: number;

  constructor(o?: SettingInterface) {
    constructByInterface(o, this);
  }
}
