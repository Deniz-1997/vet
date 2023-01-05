import {LocationInterface, LocationModel} from 'src/app/models/location/location.model';
import {constructByInterface} from '../../utils/construct-by-interface';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';
import {ReferenceCountriesModel} from '../reference/reference.countries.models';

export interface AnimalLivingPlaceInterface {
  id: string;
  arrivalDate: string;
  country: ReferenceCountriesModel;
  location: LocationInterface;
  address: string;
  isCurrent: boolean;
  note: string;
  createdAt: string;
  updatedAt: string;
}

export class AnimalLivingPlaceModel implements AnimalLivingPlaceInterface {
  id: string;
  @propertyesType({type: PropertyViewType.DATE, col: 4, title: 'Дата прибытия', required: false,
    objectType: PropertyViewObjectType.ANY})
  arrivalDate: string;
  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, col: 8, required: false, title: 'Страна',
    crudType: 'ReferenceCountries', objectName: 'name', objectType: PropertyViewObjectType.OBJECT})
  country: ReferenceCountriesModel;
  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, col: 12, required: true, title: 'Место проживания',
    crudType: 'ReferenceLocation', objectName: 'address', objectType: PropertyViewObjectType.OBJECT})
  location: LocationModel;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Адресс', col: 12, required: false,
    objectType: PropertyViewObjectType.ANY})
  address: string;
  @propertyesType({type: PropertyViewType.CHECK_BOX, col: 6, required: false, label: 'Актуальный',
    objectType: PropertyViewObjectType.BOOLEAN})
  isCurrent: boolean;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Комментарии', col: 12, required: false,
    objectType: PropertyViewObjectType.ANY})
  note: string;
  createdAt: string;
  updatedAt: string;
  @propertyesType({type: PropertyViewType.FIELDS,
    fields: {
      0: 'id',
      1: 'arrivalDate',
      2: 'address',
      3: 'isCurrent',
      4: 'note',
      'country': ['name', 'id'],
      'location': ['address', 'id']}
  })
  field: object;

  constructor(o?: AnimalLivingPlaceInterface) {
    constructByInterface(o, this);
  }
}
