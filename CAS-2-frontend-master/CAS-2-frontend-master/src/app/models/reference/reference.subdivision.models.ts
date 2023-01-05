import {constructByInterface} from '../../utils/construct-by-interface';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';
import {UserInterface, UserModels} from '../user/user.models';
import {ReferenceStationModel} from './reference.station.models';

export interface ReferenceSubdivisionInterface {
  id: number;
  name: string;
  station: ReferenceStationModel;
  users: Array<UserInterface>;
  deleted: boolean;
}

export class ReferenceSubdivisionModel {
  id: number;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 12, required: true})
  name: string;
  @propertyesType({
    type: PropertyViewType.AUTOCOMPLETE, title: 'Станция', col: 6, required: true, crudType: 'ReferenceStation',
    label: 'station', objectName: 'name', objectType: PropertyViewObjectType.OBJECT
  })
  station: ReferenceStationModel;
  @propertyesType({
    type: PropertyViewType.CHECK_BOX, col: 6, required: true, label: 'Приватное',
    objectType: PropertyViewObjectType.BOOLEAN
  })
  isPrivate: boolean;
  deleted: boolean;
  @propertyesType({
    type: PropertyViewType.FIELDS,
    fields: {
      0: 'id',
      1: 'name',
      2: 'isPrivate',
      'station': ['name', 'id']
    }
  })
  field: object;

  constructor(o?: ReferenceSubdivisionInterface) {
    constructByInterface(o, this);
  }
}
