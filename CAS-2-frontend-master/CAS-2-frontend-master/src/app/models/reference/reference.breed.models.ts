import {constructByInterface} from '../../utils/construct-by-interface';
import {ReferencePetTypeInterface, ReferencePetTypeModel} from './reference.pet.type.models';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';
import {KindModel} from '../dictionary/kind.model';

export interface ReferenceBreedInterface {
  id: number;
  name: string;
  type: ReferencePetTypeInterface;
  deleted: boolean;
}

export class ReferenceBreedModel {
  'id': number;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 10, required: true})
  'name': string;
  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, col: 6, required: true, objectName: 'name',
    objectType: PropertyViewObjectType.OBJECT})
  'type': KindModel;
  'deleted': boolean;
  @propertyesType({type: PropertyViewType.FIELDS,
    fields: {
      0: 'id',
      1: 'name',
      'type': ['name', 'id']}
  })
  field: object;

  constructor(o?: ReferenceBreedInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
