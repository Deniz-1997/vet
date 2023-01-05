import {constructByInterface} from '../../utils/construct-by-interface';
import {AnimalInterface, AnimalModel} from '../animal/animal.model';
import {VaccineSeriesInterface, VaccineSeriesModel} from '../dictionary/vaccine-series.model';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';
import {UserModels} from '../user/user.models';
import {ReferenceStationModel} from '../reference/reference.station.models';

export interface VaccinationInterface {
  id: number;
  animals: Array<AnimalInterface>;
  vaccineSeries: Array<VaccineSeriesInterface>;
  date: string;
  createdBy: UserModels;
  station: ReferenceStationModel;
  people: Array<UserModels>;
  doctor: string;
}

export class VaccinationModel implements VaccinationInterface {
  id: number;
  @propertyesType({type: PropertyViewType.MULTISELECT, col: 6, title: 'Животные', required: false, crudType: 'DictionaryAnimal',
    objectType: PropertyViewObjectType.ARRAY_OBJECT, objectName: 'name'})
  animals: Array<AnimalModel>;
  @propertyesType({type: PropertyViewType.MULTISELECT, col: 6, title: 'Серия вакцины', required: false, crudType: 'DictionaryVaccineSeries',
    objectType: PropertyViewObjectType.ARRAY_OBJECT, objectName: 'serialNumber'})
  vaccineSeries: Array<VaccineSeriesModel>;
  @propertyesType({type: PropertyViewType.DATE, col: 6, title: 'Дата', required: false,
    objectType: PropertyViewObjectType.ANY})
  date: string;
  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, col: 6, title: 'Создатель', required: true, crudType: 'User',
    objectName: 'name', objectType: PropertyViewObjectType.OBJECT})
  createdBy: UserModels;
  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, col: 6, title: 'Станция', required: true, crudType: 'ReferenceStation',
    objectName: 'name', objectType: PropertyViewObjectType.OBJECT})
  station: ReferenceStationModel;
  @propertyesType({type: PropertyViewType.MULTISELECT, col: 6, title: 'Люди', required: false, crudType: 'User',
    objectType: PropertyViewObjectType.ARRAY_OBJECT, objectName: 'name'})
  people: Array<UserModels>;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 6, required: true})
  doctor: string;
  @propertyesType({type: PropertyViewType.FIELDS,
    fields: {
      0: 'id',
      1: 'date',
      'animals': ['name', 'id'],
      'createdBy': ['name', 'id'],
      'station': ['name', 'id'],
      'people': ['name', 'id'],
    }
  })
  field: object;

  constructor(o?: VaccinationInterface) {
    constructByInterface(o, this);
  }
}

