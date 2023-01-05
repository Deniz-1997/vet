import {constructByInterface} from '../../utils/construct-by-interface';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';
import {UserInterface, UserModels} from '../user/user.models';

export interface ReferenceStationInterface {
  id: number;
  name: string;
  parent: ReferenceStationModel;
  users: Array<UserInterface>;
  deleted: boolean;
}

export class ReferenceStationModel {
  id: number;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 12, required: true})
  name: string;
  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, title: 'Станции', col: 6, required: true, crudType: 'ReferenceStation',
    label: 'station', objectName: 'name', objectType: PropertyViewObjectType.OBJECT})
  parent: ReferenceStationModel;
  @propertyesType({type: PropertyViewType.MULTISELECT, title: 'Пользователь', col: 6, required: false, crudType: 'User',
    objectName: 'name', objectType: PropertyViewObjectType.ARRAY_OBJECT})
  users: Array<UserModels>;
  deleted: boolean;
  @propertyesType({type: PropertyViewType.FIELDS,
    fields: {
      0: 'id',
      1: 'name',
      'users': ['name', 'id', 'username'],
      'parent': ['name', 'id']}
  })
  field: object;

  constructor(o?: ReferenceStationInterface) {
    constructByInterface(o, this);
  }
}
