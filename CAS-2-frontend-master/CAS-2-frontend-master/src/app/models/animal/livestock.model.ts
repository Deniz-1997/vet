import {constructByInterface} from '../../utils/construct-by-interface';
import {ReferenceSupervisedObjectModel} from '../reference/reference.supervisedObects.models';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';

export interface LivestockInterface {
  id: string;
  supervisedObject: ReferenceSupervisedObjectModel;
  // individual: IndividualInterface;
  livestockType: string;
  qty: number;
}

export class LivestockModel implements LivestockInterface {
    id: string;
    @propertyesType({type: PropertyViewType.AUTOCOMPLETE, col: 4, title: 'Поднадзорные объекты', required: true, crudType: 'ReferenceSupervisedObject',
    objectName: 'name', objectType: PropertyViewObjectType.OBJECT})
    supervisedObject: ReferenceSupervisedObjectModel;
    // individual: IndividualInterface;
    @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Тип скота', col: 6, required: true})
    livestockType: string;
    @propertyesType({type: PropertyViewType.INPUT_INT, title: 'Номер', col: 6, required: true})
    qty: number;
  @propertyesType({type: PropertyViewType.FIELDS,
    fields: {
      0: 'id',
      1: 'livestockType',
      2: 'qty',
      'supervisedObject': ['name', 'id']}
  })
  field: object;

  constructor(o?: LivestockInterface) {
    constructByInterface(o, this);
  }
}
