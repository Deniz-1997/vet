import {constructByInterface} from '../../utils/construct-by-interface';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';
import {KindModel} from './kind.model';

export interface BreedInterface {
  id: string;
  name: string;
  kind: KindModel;
  createdAt: string;
  updatedAt: string;
}

export class BreedModel implements BreedInterface {
  id: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 12, required: true})
  name: string;
  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, col: 4, title: 'Вид', required: true, crudType: 'DictionaryKind',
    objectName: 'name', objectType: PropertyViewObjectType.OBJECT})
  kind: KindModel;
  createdAt: string;
  updatedAt: string;
  @propertyesType({type: PropertyViewType.FIELDS,
    fields: {
      0: 'id',
      1: 'name',
      'kind': ['name', 'id']}
  })
  field: object;

  constructor(o?: BreedInterface) {
    constructByInterface(o, this);
  }
}
