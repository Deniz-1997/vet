import {ListFilterTypeEnum} from './list-filter.enum';
import {constructByInterface} from '../../../../../utils/construct-by-interface';
import {BehaviorSubject, Observable} from 'rxjs';
import {CrudType} from 'src/app/common/crud-types';

export interface ListFilterElementInterface {
  value?: string;
  class?: string;
  style?: string;
  options?: Array<any> | Observable<Array<any>>;
  optionsType?: CrudType;
  optionsFilter?: BreedFilterTypeInterface | BehaviorSubject<Array<any>>;
  min?: any;
  max?: any;
  label?: string;
}

export class ListFilterElementModel implements ListFilterElementInterface {
  value = '';
  class = '';
  style = '';
  options: Array<any> = [];
  optionsFilter: BreedFilterTypeInterface;
  min: any;
  max: any;
  label: string;

  constructor(o?: ListFilterElementInterface) {
    constructByInterface(o, this);
  }
}

export interface ListFilterFieldInterface {
  head?: ListFilterElementInterface;
  body?: ListFilterElementInterface;
  type: ListFilterTypeEnum | string;
  field?: string;
  mutableSearchType?: CrudType;
  attributes?: ListFilterElementInterface;
  prop: string;
  style?: string;
  class?: string;
  loading?: boolean;
}

export class ListFilterFieldModel implements ListFilterFieldInterface {
  head: ListFilterElementModel;
  body: ListFilterElementModel;
  type: ListFilterTypeEnum | string = ListFilterTypeEnum.text;
  attributes: ListFilterElementModel;
  prop = 'property';
  style = '';
  class = '';
  loading = false;

  constructor(o?: ListFilterFieldInterface) {
    constructByInterface(o, this, {
      head: ListFilterElementModel,
      body: ListFilterElementModel,
      attributes: ListFilterElementModel
    });
  }
}

export interface FilterTypeInterface {
  name: string;
}

export interface BreedFilterTypeInterface extends FilterTypeInterface {
  name: 'breed';
  prop: string;
}
