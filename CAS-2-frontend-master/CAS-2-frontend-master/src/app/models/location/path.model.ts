import {constructByInterface} from '../../utils/construct-by-interface';
import {LocationInterface, LocationModel} from './location.model';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';

export interface PathInterface {
  id: string;
  location: LocationInterface;
  groupNum: number;
  buildOrder: number;
  included: boolean;
  data: string;
  parent: PathModel;
}

export class PathModel implements PathInterface {
    id: string;
    @propertyesType({type: PropertyViewType.AUTOCOMPLETE, col: 6, title: 'Родитель', required: false, crudType: 'ReferencePath',
    objectName: 'data', objectType: PropertyViewObjectType.OBJECT})
    parent: PathModel;
    @propertyesType({type: PropertyViewType.AUTOCOMPLETE, col: 6, title: 'Местоположение', required: true, crudType: 'ReferenceLocation',
    objectName: 'address', objectType: PropertyViewObjectType.OBJECT})
    location: LocationModel;
    @propertyesType({type: PropertyViewType.INPUT_INT, title: 'Номер группы', col: 6, required: true})
    groupNum: number;
    @propertyesType({type: PropertyViewType.INPUT_INT, title: 'Номер заказа', col: 6, required: true})
    buildOrder: number;
    @propertyesType({type: PropertyViewType.CHECK_BOX, col: 6, required: false, label: 'Включены',
    objectType: PropertyViewObjectType.BOOLEAN})
    included: boolean;
    @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Значение', col: 6, required: true})
    data: string;
    @propertyesType({type: PropertyViewType.FIELDS,
      fields: {
        0: 'id',
        1: 'address',
        2: 'center',
        3: 'groupNum',
        4: 'buildOrder',
        5: 'included',
        6: 'data',
        'parent': ['data', 'id'],
        'location': ['address', 'id']}
    })
    field: object;

  constructor(o?: PathInterface) {
    constructByInterface(o, this);
  }
}
