import {constructByInterface} from '../../utils/construct-by-interface';
import {AnimalInterface, AnimalModel} from './animal.model';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';

export interface AnimalStampInterface {
  id: string;
  name: string;
  stampDate: string;
  isCurrent: boolean;
  animal: AnimalInterface;
  createdAt: string;
  updatedAt: string;
}

export class AnimalStampModel implements AnimalStampInterface {
  id: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, col: 6, title: 'Наименование', required: true,
    objectType: PropertyViewObjectType.ANY})
  name: string;
  @propertyesType({type: PropertyViewType.DATE, col: 6, title: 'Дата печати', required: false,
    objectType: PropertyViewObjectType.ANY})
  stampDate: string;
  @propertyesType({type: PropertyViewType.CHECK_BOX, col: 6, required: true, label: 'Актуальный',
    objectType: PropertyViewObjectType.BOOLEAN})
  isCurrent: boolean;
  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, col: 6, required: true, title: 'Животное',
    crudType: 'DictionaryAnimal', objectName: 'name', objectType: PropertyViewObjectType.OBJECT})
  animal: AnimalModel;
  createdAt: string;
  updatedAt: string;
  @propertyesType({type: PropertyViewType.FIELDS,
    fields: {
      0: 'id',
      1: 'name',
      2: 'stampDate',
      3: 'isCurrent',
      'animal': ['name', 'id']}
  })
  field: object;

  constructor(o?: AnimalStampInterface) {
    constructByInterface(o, this);
  }
}
