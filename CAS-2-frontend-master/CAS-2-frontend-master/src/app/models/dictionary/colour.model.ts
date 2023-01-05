import {constructByInterface} from '../../utils/construct-by-interface';
import {propertyesType, PropertyViewType} from '../../modules/shared/decorators/property-type.decorator';

export interface ColourInterface {
  id: string;
  name: string;
  createdAt: string;
  updatedAt: string;
}

export class ColourModel implements ColourInterface {
  id: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 12, required: true})
  name: string;
  createdAt: string;
  updatedAt: string;

  constructor(o?: ColourInterface) {
    constructByInterface(o, this);
  }
}
