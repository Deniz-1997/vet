import {constructByInterface} from '../../utils/construct-by-interface';
import {
  propertyesType, PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';

export interface ReferenceCountriesInterface {
  id: number;
  name: string;
  deleted: boolean;
  sort: number;
  iso: string;
}

export class ReferenceCountriesModel implements ReferenceCountriesInterface {
  id: number;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 6, required: true})
  name: string;
  deleted: boolean;
  sort: number;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Цифровой ISO-код', col: 6, required: true,  objectType: PropertyViewObjectType.ANY})
  iso: string;


  constructor(o?: ReferenceCountriesInterface) {
    constructByInterface(o, this);
  }
}
