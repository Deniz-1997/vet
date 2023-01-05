import { DataType } from '../api-connector.utils';

export interface CrudTypeUnitInterface {
  url: string;
  params?: Array<string>;
  model: object;
}

export class CrudTypeUnit {
  private _url: string;

  get url(): string {
    if (this.params && this.params.length > 0) {
      let url = this._url;
      for (const i in this.params) {
        if (this.params.hasOwnProperty(i)) {
          let rep = '';
          if (this.data && this.data.hasOwnProperty(this.params[i]) && this.data[this.params[i]]) {
            rep = this.data[this.params[i]];
          }
          url = url.replace(':' + this.params[i], rep);
        }
      }
      url = url.replace('//', '/');
      return url;
    }
    return this._url;
  }

  set url(value: string) {
    this._url = value;
  }

  idProp = 'id';
  params: Array<string> = [];
  model: object = {};
  data: object = {};

  setData(data: object | string | number): any {
    if (typeof data === 'string' || typeof data === 'number') {
      data = {id: data};
    }
    if (typeof data === 'undefined') {
      data = {};
    }
    if (data && data.hasOwnProperty('urlParams')) {
      this.data = {...data['urlParams']};
      delete data['urlParams'];
    } else {
      this.data = data;
    }
    return this;
  }

  constructor(o?: CrudTypeUnitInterface) {
    if (o) {
      Object.keys(o).forEach(key => {
        this[key] = o[key];
      });
    }
  }
}

export type CrudDataType = any;
export type CrudDataInterface = any;

export interface CrudParams<T = any> {
  type: string;
  params?: T;
  fields?: any;
  onSuccess?: (response: any) => any;
  onError?: (error: any) => any;
  onComplete?: () => any;
  dataType?: DataType;
  url?: string;
  loaded?: boolean;
}

export interface CrudStoreInterface<T = any> {
  postLoading: boolean;
  patchLoading: boolean;
  deleteLoading: boolean;
  getLoading: boolean;
  getListLoading: boolean;
  appendListLoading: boolean;
  data: { [id: number]: T };
  totalCount: number;
  dataIds: Array<number>;
  matches: Array<T>;
  matchesLoading: boolean;
  loaded: boolean;
  columns?: Array<{
    description?: string,
    path: string,
    title: string,
    format?: string,
    tags?: Array<string>,
  }>;
}

export const initial: CrudStoreInterface = {
  postLoading: false,
  patchLoading: false,
  deleteLoading: false,
  getLoading: false,
  getListLoading: false,
  appendListLoading: false,
  data: {},
  totalCount: null,
  dataIds: [],
  matches: [],
  matchesLoading: false,
  loaded: false,
  columns: [],
};
