import {constructByInterface} from '../../utils/construct-by-interface';
import {propertyesType, PropertyViewType} from '../../modules/shared/decorators/property-type.decorator';

export interface KindInterface {
  id: string;
  name: string;
  createdAt: string;
  updatedAt: string;
}

export class KindModel implements KindInterface {
  id: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 12, required: true})
  name: string;
  createdAt: string;
  updatedAt: string;

  constructor(o?: KindInterface) {
    constructByInterface(o, this);
  }
}
