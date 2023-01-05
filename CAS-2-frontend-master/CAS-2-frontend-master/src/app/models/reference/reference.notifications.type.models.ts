import {constructByInterface} from '../../utils/construct-by-interface';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';

export interface ReferenceNotificationsTypeInterface {
  id: number;
  name: string;
  template: string;
  required: boolean;
}

export class ReferenceNotificationsTypeModel {
  'id': number;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 12, required: true, })
  'name': string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Шаблон', col: 12, required: true, objectType: PropertyViewObjectType.ANY})
  'template': string;
  @propertyesType({type: PropertyViewType.CHECK_BOX, col: 3, label: 'Необязательно', objectType: PropertyViewObjectType.BOOLEAN})
  'required': boolean;

  constructor(o?: ReferenceNotificationsTypeInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
