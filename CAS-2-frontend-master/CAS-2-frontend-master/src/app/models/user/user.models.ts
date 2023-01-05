import {ElementDefInterface} from '../element.def.models';
import {constructByInterface} from '../../utils/construct-by-interface';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';
import {ReferenceStationInterface, ReferenceStationModel} from '../reference/reference.station.models';
import {ReferenceBusinessEntityModel} from '../reference/reference.businessEntity.models';

export interface UserInterface extends ElementDefInterface {
  groups: Array<any>;
  username: string;
  name?: string;
  surname: string;
  patronymic?: string;
  email: string;
  password: string;
  salt: string;
  confirmationChangePasswordCode: string;
  confirmationChangePasswordRecipient: string;
  confirmationChangePasswordCodeCreatedAt: string;
  additionalRestrictions: object;
  additionalFields: object;
  phoneNumber: string;
  status: boolean;
  modeCashboxMobile: boolean;
  stations: Array<ReferenceStationInterface>;
  cashboxDeviceId: number | null;
}

export class UserModels implements UserInterface {
  id?: number | null;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Имя', col: 12, required: true,
    objectType: PropertyViewObjectType.ANY})
  name?: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Фамилия', col: 12, required: true,
    objectType: PropertyViewObjectType.ANY})
  surname: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Отчество', col: 12, required: true,
    objectType: PropertyViewObjectType.ANY})
  patronymic?: string;
  deleted?: boolean;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Группы', col: 12, required: true,
    objectType: PropertyViewObjectType.ARRAY_OBJECT, objectName: 'name'})
  groups: Array<any>;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Логин', col: 12, required: true,
    objectType: PropertyViewObjectType.ANY})
  username: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Email', col: 12, required: true,
    objectType: PropertyViewObjectType.ANY})
  email: string;
  password: string;
  salt: string;
  confirmationChangePasswordCode: string;
  confirmationChangePasswordRecipient: string;
  confirmationChangePasswordCodeCreatedAt: string;
  additionalRestrictions: object;
  additionalFields: object;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Номер телефона', col: 12, required: true,
    objectType: PropertyViewObjectType.ANY})
  phoneNumber: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Статус', col: 12, required: true,
    objectType: PropertyViewObjectType.BOOLEAN})
  status: boolean;
  @propertyesType({type: PropertyViewType.MULTISELECT, title: 'Станции', col: 6, required: false, crudType: 'ReferenceStation',
    objectName: 'name', objectType: PropertyViewObjectType.ARRAY_OBJECT})
  stations: Array<ReferenceStationModel>;
  @propertyesType({type: PropertyViewType.MULTISELECT, title: 'Хоз. субъекты', col: 6, required: true,
    crudType: 'ReferenceBusinessEntity', objectName: 'name', objectType: PropertyViewObjectType.ARRAY_OBJECT, linkString: `/reference/organization/business-entity/`})
  businessEntity: ReferenceBusinessEntityModel;
  modeCashboxMobile: boolean;
  cashboxDeviceId: number | null;
  @propertyesType({type: PropertyViewType.FIELDS,
    fields: {
      0: 'id',
      1: 'name',
      2: 'surname',
      3: 'patronymic',
      4: 'groups',
      5: 'username',
      6: 'email',
      7: 'phoneNumber',
      8: 'status',
      9: 'additionalFields',
      'stations': ['name', 'id']}
  })
  field: object;

  constructor(o: UserInterface) {
    constructByInterface(o, this);
  }

  getFullName(): string {
    return ((this.surname || '') + ' ' + (this.name || '') + ' ' + (this.patronymic || '')).trim();
  }
}
