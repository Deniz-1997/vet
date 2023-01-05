import {constructByInterface} from '../../utils/construct-by-interface';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';
import {ReferenceCountriesModel} from '../reference/reference.countries.models';

export interface ManufacturerInterface {
  id: string;
  name: string;
  country: ReferenceCountriesModel;
}

export class ManufacturerModel implements ManufacturerInterface {
  id: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 12, required: true})
  name: string;
  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, col: 6, title: 'Страна', required: false, crudType: 'ReferenceCountries',
    objectName: 'name', objectType: PropertyViewObjectType.OBJECT})
  country: ReferenceCountriesModel;
  @propertyesType({type: PropertyViewType.FIELDS,
    fields: {
      0: 'id',
      1: 'name',
      'country': ['name', 'id']}
  })
  field: object;

  constructor(o?: ManufacturerInterface) {
    constructByInterface(o, this);
  }
}
