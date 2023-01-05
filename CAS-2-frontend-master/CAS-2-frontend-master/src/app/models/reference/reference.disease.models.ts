import {constructByInterface} from '../../utils/construct-by-interface';
import {
  propertyesType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';

export interface ReferenceDiseaseInterface {
  id: number;
  name: string;
  deleted: boolean;
  sort: number;
}

export class ReferenceDiseaseModel implements ReferenceDiseaseInterface {
  id: number;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 12})
  name: string;
  deleted: boolean;
  sort: number;


  constructor(o?: ReferenceDiseaseInterface) {
    constructByInterface(o, this);
  }
}
