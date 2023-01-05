import {constructByInterface} from '../../utils/construct-by-interface';
import {DiseaseInterface, DiseaseModel} from './disease.model';
import {ManufacturerInterface, ManufacturerModel} from './manufacturer.model';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';

export interface VaccineInterface {
  id: number;
  name: string;
  manufacturer: ManufacturerInterface;
  diseases: Array<DiseaseInterface>;
  invalid: boolean;
  activityDuration: number;
}

export class VaccineModel implements VaccineInterface {
  id: number;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 12, required: true})
  name: string;
  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, col: 6, title: 'Производитель', required: true, crudType: 'DictionaryManufacturer',
    objectName: 'name', objectType: PropertyViewObjectType.OBJECT})
  manufacturer: ManufacturerModel;
  @propertyesType({type: PropertyViewType.MULTISELECT, col: 6, title: 'Заболевания', required: false, crudType: 'DictionaryDisease',
    objectType: PropertyViewObjectType.ARRAY_OBJECT, objectName: 'name'})
  diseases: Array<DiseaseModel>;
  invalid: boolean;
  @propertyesType({type: PropertyViewType.INPUT_INT, title: 'Продолжительность деятельности', col: 12, required: true,
    objectType: PropertyViewObjectType.ANY})
  activityDuration: number;
  @propertyesType({type: PropertyViewType.FIELDS,
    fields: {
      0: 'id',
      1: 'name',
      2: 'activityDuration',
      'manufacturer': ['name', 'id'],
      'diseases': ['name', 'id']}
  })
  field: object;

  constructor(o?: VaccineInterface) {
    constructByInterface(o, this);
  }
}
