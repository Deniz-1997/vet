import {constructByInterface} from '../utils/construct-by-interface';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../modules/shared/decorators/property-type.decorator';

export interface RoleInterface {
  id?: number;
  name: string;
  enabled: boolean;
  file: string;
  code: string;
  type: string;
}

export class RoleModel implements RoleInterface {
  id: number;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 10, required: true})
  name: string;
  enabled: boolean;
  file: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Код', col: 10, required: true,
    objectType: PropertyViewObjectType.ANY})
  code: string;
  type: string;

  constructor(o?: RoleInterface) {
    constructByInterface(o, this);
  }
}
