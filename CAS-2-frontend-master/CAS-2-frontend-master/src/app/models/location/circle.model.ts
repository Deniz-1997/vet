import {constructByInterface} from '../../utils/construct-by-interface';
import {LocationInterface, LocationModel} from './location.model';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';

export interface CircleInterface {
  id: string;
  location: LocationInterface;
  groupNum: number;
  buildOrder: number;
  included: boolean;
  center: string;
  radius: number;
}

export class CircleModel implements CircleInterface {
    id: string;
    @propertyesType({type: PropertyViewType.AUTOCOMPLETE, col: 6, title: 'Местоположение', required: true, crudType: 'ReferenceLocation',
    objectName: 'address', objectType: PropertyViewObjectType.OBJECT})
    location: LocationModel;
    @propertyesType({type: PropertyViewType.INPUT_INT, title: 'Номер группы', col: 6, required: true,  objectType: PropertyViewObjectType.ANY})
    groupNum: number;
    @propertyesType({type: PropertyViewType.INPUT_INT, title: 'Номер заказа', col: 6, required: true,  objectType: PropertyViewObjectType.ANY})
    buildOrder: number;
    @propertyesType({type: PropertyViewType.CHECK_BOX, col: 6, required: false, label: 'Включены',
    objectType: PropertyViewObjectType.BOOLEAN})
    included: boolean;
    @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Центр', col: 6, required: true,  objectType: PropertyViewObjectType.ANY})
    center: string;
    @propertyesType({type: PropertyViewType.INPUT_INT, title: 'Радиус', col: 6, required: true,  objectType: PropertyViewObjectType.ANY})
    radius: number;
    @propertyesType({type: PropertyViewType.FIELDS,
      fields: {
        0: 'id',
        1: 'groupNum',
        2: 'buildOrder',
        3: 'included',
        4: 'center',
        5: 'radius',
        'location': ['address', 'id']}
    })
    field: object;

  constructor(o?: CircleInterface) {
    constructByInterface(o, this);
  }
}
