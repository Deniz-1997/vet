import {LocationInterface, LocationModel} from 'src/app/models/location/location.model';
import {constructByInterface} from '../../utils/construct-by-interface';
import {BreedInterface, BreedModel} from '../dictionary/breed.model';
import {ColourInterface, ColourModel} from '../dictionary/colour.model';
import {KindInterface, KindModel} from '../dictionary/kind.model';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';

export interface AnimalInterface {
  id: number;
  name: string;
  kind: KindInterface;
  breed: BreedInterface;
  colour: ColourInterface;
  location: LocationInterface;
  birthdate: string;
  owner: string;
  chip: string;
  createdAt: string;
  updatedAt: string;
  gender: {
    code: string;
    title: string;
  };
}

export class AnimalModel implements AnimalInterface {
  id: number;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 12, required: true})
  name: string;
  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, col: 4, title: 'Вид', required: true, crudType: 'DictionaryKind',
    objectName: 'name', objectType: PropertyViewObjectType.OBJECT})
  kind: KindModel;
  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, col: 4, title: 'Порода', required: true, crudType: 'DictionaryBreed',
    objectName: 'name', objectType: PropertyViewObjectType.OBJECT})
  breed: BreedModel;
  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, col: 4, title: 'Цвет', required: false, crudType: 'DictionaryColour',
    objectName: 'name', objectType: PropertyViewObjectType.OBJECT})
  colour: ColourModel;
  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, col: 6, title: 'Местоположение', required: true, crudType: 'ReferenceLocation',
    objectName: 'address', objectType: PropertyViewObjectType.OBJECT})
  location: LocationModel;
  @propertyesType({type: PropertyViewType.DATE, col: 6, title: 'Дата рождения', required: false,
    objectType: PropertyViewObjectType.ANY})
  birthdate: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Хозяин', col: 12, required: true})
  owner: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, col: 4, title: 'Чип', required: false,
    objectType: PropertyViewObjectType.ANY})
  chip: string;
  @propertyesType({type: PropertyViewType.SELECT, title: 'Пол', col: 4, required: true, crudType: 'AnimalGenderEnum',
    objectName: 'title', objectType: PropertyViewObjectType.OBJECT} )
  gender: {
    code: string;
    title: string;
  };
  createdAt: string;
  updatedAt: string;
  @propertyesType({type: PropertyViewType.FIELDS,
    fields: {
      0: 'id',
      1: 'name',
      2: 'birthdate',
      3: 'owner',
      4: 'chip',
      5: 'gender',
      'kind': ['name', 'id'],
      'breed': ['name', 'id'],
      'colour': ['name', 'id'],
      'location': ['address', 'id']}
  })
  field: object;

  constructor(o?: AnimalInterface) {
    constructByInterface(o, this);
  }
}
