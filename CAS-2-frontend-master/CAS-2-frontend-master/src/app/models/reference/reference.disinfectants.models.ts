import {constructByInterface} from '../../utils/construct-by-interface';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';
import {ReferenceMeasurementUnitsInterface, ReferenceMeasurementUnitsModel} from './reference.measurement.units.models';

export interface ReferenceDisinfectantsInterface {
  id: number;
  name: string;
  measurementUnits: ReferenceMeasurementUnitsInterface;
  kind: string;
  mult: number;
}

export class ReferenceDisinfectantsModel {
  'id': number;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 6, required: true})
  name: string;
  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, col: 6, title: 'Ед. измерения', required: false, objectName: 'name',
    crudType: 'ReferenceMeasurementUnits',
    objectType: PropertyViewObjectType.OBJECT})
  measurementUnits: ReferenceMeasurementUnitsModel;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Вид', col: 6, required: false, objectType: PropertyViewObjectType.ANY})
  kind: string;
  @propertyesType({type: PropertyViewType.INPUT_INT, title: 'Количество', col: 6, required: true, objectType: PropertyViewObjectType.ANY})
  mult: number;
  @propertyesType({type: PropertyViewType.FIELDS,
    fields: {
      0: 'id',
      1: 'name',
      2: 'kind',
      3: 'mult',
      'measurementUnits': ['name', 'id']}
  })
  field: object;

  constructor(o?: ReferenceDisinfectantsInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
