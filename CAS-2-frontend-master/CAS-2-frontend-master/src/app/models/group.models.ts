import {constructByInterface} from '../utils/construct-by-interface';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../modules/shared/decorators/property-type.decorator';

export interface GroupInterface {
  id: number;
  name: string;
  code: string;
  externalId?: number;
}

export class GroupModel implements GroupInterface {
  id: number;
  name: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Текстовый идентификатор', col: 12, required: true,
    objectType: PropertyViewObjectType.ANY})
  code: string;
  externalId?: number;

  constructor(o?: GroupInterface) {
    constructByInterface(o, this);
  }
}
