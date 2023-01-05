import {constructByInterface} from '../../utils/construct-by-interface';
import {VaccinationInterface, VaccinationModel} from '../vaccination/vaccination.model';
import {VaccineInterface, VaccineModel} from './vaccine.model';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';

export interface VaccineSeriesInterface {
  id: number;
  serialNumber: string;
  produced: string;
  expires: string;
  vaccine: VaccineInterface;
  isInvalid: boolean;
}

export class VaccineSeriesModel implements VaccineSeriesInterface {
  id: number;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Серийный номер', col: 6, required: true,
    objectType: PropertyViewObjectType.ANY})
  serialNumber: string;
  @propertyesType({type: PropertyViewType.DATE, title: 'Произведен', col: 6, required: true,
    objectType: PropertyViewObjectType.ANY})
  produced: string;
  @propertyesType({type: PropertyViewType.DATE, title: 'Срок действия', col: 6, required: true,
    objectType: PropertyViewObjectType.ANY})
  expires: string;
  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, col: 6, title: 'Вакцина', required: true, crudType: 'DictionaryVaccine',
    objectName: 'name', objectType: PropertyViewObjectType.OBJECT})
  vaccine: VaccineModel;
  isInvalid: boolean;
  @propertyesType({type: PropertyViewType.FIELDS,
    fields: {
      0: 'id',
      1: 'serialNumber',
      2: 'produced',
      3: 'expires',
      'vaccine': ['name', 'id'], }
  })
  field: object;

  constructor(o?: VaccinationInterface) {
    constructByInterface(o, this);
  }
}
