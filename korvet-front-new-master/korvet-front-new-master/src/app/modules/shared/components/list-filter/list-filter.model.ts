import {ListFilterTypeEnum} from './list-filter.enum';
import {constructByInterface} from '../../../../utils/construct-by-interface';
import {BehaviorSubject, Observable} from 'rxjs';
import {CrudType} from 'src/app/common/crud-types';

export interface ListFilterElementInterface {
  value?: string;
  class?: string;
  style?: string;
  options?: any[] | Observable<any[]>;
  optionsType?: CrudType;
  optionsFilter?: BreedFilterTypeInterface | BehaviorSubject<any[]>;
  min?: any;
  max?: any;
  label?: string;
  placeholder?: string;
}

export class ListFilterElementModel implements ListFilterElementInterface {
  value = '';
  class = '';
  style = '';
  options: any[] = [];
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
  placeholder?: string;
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
  placeholder = '';

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
