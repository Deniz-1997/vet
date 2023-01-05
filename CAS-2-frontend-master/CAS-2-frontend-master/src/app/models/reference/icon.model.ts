import {constructByInterface} from '../../utils/construct-by-interface';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';

export interface ReferenceIconInterface {
  id?: number;
  name: string;
  deleted: boolean;
  class: string;
  code: string;
}

export class ReferenceIconModel implements ReferenceIconInterface {
  id: number;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 12, required: true})
  name: string;
  deleted: boolean;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Класс', col: 12, objectType: PropertyViewObjectType.ANY})
  class: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Код', col: 12, required: true,
    objectType: PropertyViewObjectType.ANY})
  code: string;
  @propertyesType({type: PropertyViewType.FIELDS,
    fields: {
      0: 'id',
      1: 'name',
      2: 'class',
      3: 'code'}
  })
  field: object;

  constructor(o?: ReferenceIconInterface) {
    constructByInterface(o, this);
  }
}
