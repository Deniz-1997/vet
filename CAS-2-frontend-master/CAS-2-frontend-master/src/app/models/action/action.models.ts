import {constructByInterface} from '../../utils/construct-by-interface';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';

export interface ActionInterface {
  name: string;
  code: string;
  description: string;

  parentId: number;
  groups: any;
  sort: number;
  parent: any;

  type: {
    code: string;
    title: string;
  };
  url: string;
  entityClass: string; // 'App\\Entity\\Security\\Role'

  buttonSettings: {
    color: string;
    backgroundColor: string;
  };
  confirmation: {
    title: string;
    description: string;
    confirmButtonSettings: {
      color: string;
      backgroundColor: string;
    }
    cancelButtonSettings: {
      color: string;
      backgroundColor: string;
    }
  };

  itemsCountEnabled: boolean;
  itemsCount: number;

  getListEnabled: boolean;
  viewItemEnabled: boolean;
  getItemEnabled: boolean;
}

export class ActionModel {
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 10, required: true})
  name: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Текстовый идентификатор', col: 10,
    required: true, objectType: PropertyViewObjectType.ANY})
  code: string;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Описание', col: 10, required: true,
    objectType: PropertyViewObjectType.ANY})
  description: string;

  parentId: number;
  @propertyesType({type: PropertyViewType.MULTISELECT, title: 'Группы', col: 10, required: true,
    objectType: PropertyViewObjectType.ARRAY_OBJECT, objectName: 'name'})
  groups: any;
  @propertyesType({type: PropertyViewType.AUTOCOMPLETE, title: 'Родитель', col: 10, required: true,
    objectType: PropertyViewObjectType.OBJECT, objectName: 'name'})
  parent: any;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Сортировка', col: 10, required: true,
    objectType: PropertyViewObjectType.ANY})
  sort: number;

  type: {
    code: string;
    title: string;
  };
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'URL', col: 10, required: true,
    objectType: PropertyViewObjectType.ANY})
  url: string;
  entityClass: string; // 'App\\Entity\\Security\\Role'

  buttonSettings: {
    color: string;
    backgroundColor: string;
  };
  confirmation: {
    title: string;
    description: string;
    confirmButtonSettings: {
      color: string;
      backgroundColor: string;
    }
    cancelButtonSettings: {
      color: string;
      backgroundColor: string;
    }
  };

  itemsCountEnabled: boolean;
  itemsCount: number;
  items: [];

  getListEnabled: boolean;
  viewItemEnabled: boolean;
  getItemEnabled: boolean;

  constructor(o?: ActionInterface) {
    constructByInterface(o, this);
  }
}
