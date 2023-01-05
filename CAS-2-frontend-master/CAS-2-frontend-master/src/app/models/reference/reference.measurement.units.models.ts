import {constructByInterface} from '../../utils/construct-by-interface';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';

export interface ReferenceMeasurementUnitsInterface {
  id: number;
  name: string;
  deleted: boolean;
  sort: number;
}

export class ReferenceMeasurementUnitsModel implements ReferenceMeasurementUnitsInterface {
  id: number;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 12, required: true})
  name: string;
  deleted: boolean;
  sort: number;


  constructor(o?: ReferenceMeasurementUnitsInterface) {
    constructByInterface(o, this);
  }
}
