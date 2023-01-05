import {constructByInterface} from '../../utils/construct-by-interface';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';

export interface ActionGroupInterface {
  id: number;
  deleted: boolean;
  name: string;
  code: string;
  parentId: number;
}

export class ActionGroupModel implements ActionGroupInterface {
  id: number;
  deleted: boolean;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 10, required: true})
  name: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Код', col: 10, required: true, objectType: PropertyViewObjectType.ANY})
  code: string;
  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, title: 'Родитель', col: 6, objectType: PropertyViewObjectType.ANY})
  parentId: number;

  constructor(o?: ActionGroupInterface) {
    constructByInterface(o, this);
  }
}
