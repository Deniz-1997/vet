import {constructByInterface} from '../../utils/construct-by-interface';
import {UserInterface, UserModels} from '../user/user.models';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';


export interface ReferenceBusinessEntityInterface {
  id: number;
  name: string;
  legalForms: {
    code: string;
    title: string;
  };
  inn: string;
  kpp: string;
  ogrn: string;
  bik: string;
  legalAddres: string;
  headFullName: string;
  headOffice: string;
  website: string;
  registrationDate: string;
  liquidationDate: string;
  comment: string;
  workingWithSocialObj: boolean;
  users: Array<UserInterface>;
  bank: string;
  planMonth: string;
  planSkipYear: string;
  checkingAccount: string;
  corAccount: string;
  businessSize: string;
  lastCheck: number;
  riskPoints: number;
}

export class ReferenceBusinessEntityModel implements ReferenceBusinessEntityInterface {
  id: number;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 12, required: true})
  name: string;
  @propertyesType({type: PropertyViewType.SELECT, title: 'Правовые формы', col: 4, required: true, crudType: 'LegalFormsEnum',
    objectName: 'title', objectType: PropertyViewObjectType.OBJECT} )
  legalForms: {
    code: string;
    title: string;
  };
  @propertyesType({type: PropertyViewType.INPUT_STRING,  title: 'ИНН', col: 2, required: true,
    objectType: PropertyViewObjectType.ANY})
  inn: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'КПП', col: 2, required: false,
    objectType: PropertyViewObjectType.ANY})
  kpp: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING,  title: 'ОГРН', col: 2, required: false,
    objectType: PropertyViewObjectType.ANY})
  ogrn: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'БИК', col: 2, required: false,
    objectType: PropertyViewObjectType.ANY})
  bik: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Юридический адрес (улица, дом, офис)',
    col: 12, required: true, objectType: PropertyViewObjectType.ANY})
  legalAddres: string;
  @propertyesType({type: PropertyViewType.DADATA_FULL_NAME,  title: 'ФИО руководителя', col: 12,
    required: true, objectType: PropertyViewObjectType.ANY})
  headFullName: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING,  title: 'Должность', col: 4, required: false,
    objectType: PropertyViewObjectType.ANY})
  headOffice: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Сайт', col: 4, required: false,
    objectType: PropertyViewObjectType.ANY})
  website: string;
  @propertyesType({type: PropertyViewType.DATE, title: 'Дата регистрации', col: 2, required: false,
    objectType: PropertyViewObjectType.ANY})
  registrationDate: string;
  @propertyesType({type: PropertyViewType.DATE,  title: 'Дата ликвидации', col: 2, required: false,
    objectType: PropertyViewObjectType.ANY})
  liquidationDate: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Комментарии', col: 12, required: false,
    objectType: PropertyViewObjectType.ANY})
  comment: string;
  @propertyesType({type: PropertyViewType.CHECK_BOX, col: 6, required: false, label: 'Объект использует форму электронной сертификации',
    objectType: PropertyViewObjectType.BOOLEAN})
  workingWithSocialObj: boolean;
  @propertyesType({type: PropertyViewType.MULTISELECT, title: 'Пользователь', col: 12, required: false, crudType: 'User',
    objectType: PropertyViewObjectType.ARRAY_OBJECT, objectName: 'name'})
  users: Array<UserModels>;
  bank: string;
  planMonth: string;
  planSkipYear: string;
  checkingAccount: string;
  corAccount: string;
  businessSize: string;
  lastCheck: number;
  riskPoints: number;
  @propertyesType({type: PropertyViewType.FIELDS,
    fields: {
      0: 'id',
      1: 'name',
      2: 'legalForms',
      3: 'inn',
      4: 'kpp',
      5: 'ogrn',
      6: 'bik',
      7: 'legalAddres',
      8: 'headFullName',
      9: 'headOffice',
      10: 'website',
      11: 'registrationDate',
      12: 'liquidationDate',
      13: 'comment',
      14: 'workingWithSocialObj',
      'users': ['name', 'id', 'username']}
  })
  field: object;

  constructor(o?: ReferenceBusinessEntityInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
