import {constructByInterface} from '../../utils/construct-by-interface';
import {CircleInterface, CircleModel} from './circle.model';
import {PathInterface, PathModel} from './path.model';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';

export interface LocationInterface {
  id: string;
  parent: LocationInterface;
  address: string;
  fiasId: string;
  regionFiasId: string;
  areaFiasId: string;
  cityFiasId: string;
  cityDistrictFiasId: string;
  settlementFiasId: string;
  streetFiasId: string;
  houseFiasId: string;
  center: string;
}

export class LocationModel implements LocationInterface {
  id: string;
  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, col: 6, title: 'Родитель', required: true, crudType: 'ReferenceLocation',
    objectName: 'address', objectType: PropertyViewObjectType.OBJECT})
  parent: LocationModel;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Адрес', col: 6, required: true})
  address: string;
  fiasId: string;
  regionFiasId: string;
  areaFiasId: string;
  cityFiasId: string;
  cityDistrictFiasId: string;
  settlementFiasId: string;
  streetFiasId: string;
  houseFiasId: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Центр', col: 6, required: false})
  center: string;
  @propertyesType({type: PropertyViewType.FIELDS,
    fields: {
      0: 'id',
      1: 'address',
      2: 'center',
      'parent': ['address', 'id']}
  })
  field: object;


  constructor(o?: LocationInterface) {
    constructByInterface(o, this);
  }
}
