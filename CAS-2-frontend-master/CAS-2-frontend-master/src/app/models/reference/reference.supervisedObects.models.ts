import {constructByInterface} from '../../utils/construct-by-interface';
import {UserModels} from '../user/user.models';
import {ReferenceStationInterface, ReferenceStationModel} from './reference.station.models';
import {ReferenceBusinessEntityInterface, ReferenceBusinessEntityModel} from './reference.businessEntity.models';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';


export interface ReferenceSupervisedObjectInterface {
  id: number;
  name: string;
  kpp: string;
  address: string;
  headFullName: string;
  headOffice: string;
  comment: string;
  users: Array<UserModels>;
  telephoneNumber: string;
  activityKind: string;
  email: string;
  station: ReferenceStationInterface;
  businessEntity: ReferenceBusinessEntityInterface;
  internetConnection: boolean;
  issuesCertificates: boolean;
  pushingAvailable: boolean;
  compartment: number;
  latitude: number;
  longitude: number;
}

export class ReferenceSupervisedObjectModel implements ReferenceSupervisedObjectInterface {
  id: number;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 12, required: true})
  name: string;

  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Адрес', col: 8, required: true,
    objectType: PropertyViewObjectType.ANY})
  address: string;

  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'КПП', col: 4, required: true,
    objectType: PropertyViewObjectType.ANY})
  kpp: string;

  @propertyesType({type: PropertyViewType.DADATA_FULL_NAME, title: 'ФИО руководителя', col: 12,
    required: true, objectType: PropertyViewObjectType.ANY})
  headFullName: string;

  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Должность', col: 4,
    required: true, objectType: PropertyViewObjectType.ANY})
  headOffice: string;

  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Номер телефона', col: 4,
    required: true, objectType: PropertyViewObjectType.ANY})
  telephoneNumber: string;

  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Email', col: 4,
    required: false, objectType: PropertyViewObjectType.ANY})
  email: string;

  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Вид осуществляемой деятельности', col: 12,
    required: true, objectType: PropertyViewObjectType.ANY})
  activityKind: string;

  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Комментарий', col: 12,
    required: false, objectType: PropertyViewObjectType.ANY})
  comment: string;

  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, title: 'Станции' , col: 6,
    required: true, crudType: 'ReferenceStation', objectName: 'name', objectType: PropertyViewObjectType.OBJECT})
  station: ReferenceStationModel;

  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, title: 'Хоз. субъекты', col: 6, required: true,
    crudType: 'ReferenceBusinessEntity', objectName: 'name', objectType: PropertyViewObjectType.OBJECT})
  businessEntity: ReferenceBusinessEntityModel;

  @propertyesType({type: PropertyViewType.CHECK_BOX, col: 6, required: false, label: 'Работа с социальными объектами',
    objectType: PropertyViewObjectType.BOOLEAN})
  internetConnection: boolean;

  @propertyesType({type: PropertyViewType.CHECK_BOX, col: 6, required: false,
    label: 'Объект использует форму электронной сертификации', objectType: PropertyViewObjectType.BOOLEAN})
  issuesCertificates: boolean;

  @propertyesType({type: PropertyViewType.CHECK_BOX, col: 6, required: false,
    label: 'Разрешить выгрузку в МСХ АИС НСИ и на публичную карту', objectType: PropertyViewObjectType.BOOLEAN})
  pushingAvailable: boolean;

  @propertyesType({type: PropertyViewType.MULTISELECT, title: 'Пользователь', col: 12, required: false,
    crudType: 'User', objectType: PropertyViewObjectType.OBJECT, objectName: 'name'})
  users: Array<UserModels>;

  compartment: number;
  latitude: number;
  longitude: number;
  @propertyesType({type: PropertyViewType.FIELDS,
    fields: {
      0: 'id',
      1: 'name',
      2: 'address',
      3: 'kpp',
      4: 'headFullName',
      5: 'headOffice',
      6: 'telephoneNumber',
      7: 'email',
      8: 'activityKind',
      9: 'comment',
      10: 'internetConnection',
      11: 'issuesCertificates',
      12: 'pushingAvailable',
      'users': ['name', 'id'],
      'station': ['name', 'id'],
      'businessEntity': ['name', 'id']}
  })
  field: object;



  constructor(o?: ReferenceSupervisedObjectInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}


