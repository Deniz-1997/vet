import { clearNullsDeep, constructByInterface } from './api-connector.utils';
import * as _ from 'lodash';

export interface ApiResponse<T = any> {
  errors?: Array<ErrorResponseInterface>;
  httpResponseCode?: number;
  requestId?: string; // "258838ca-c3ce-4296-8be9-891e4abf6682"
  response?: T;
  status: boolean;
  statusRequest?: 'CREATE'|'ERROR'|'WAIT'|'COMPLETE'|string;
  longOperation?: boolean;
}

export interface ItemsResponseInterface<T = any> {
  items: Array<T>;
  totalCount: number;
  columns?: Array<{
    description?: string,
    path: string,
    title: string,
    format?: string,
    tags?: Array<string>,
  }>;
}

export interface ItemsResponse<T = any> extends ApiResponse {
  response: ItemsResponseInterface<T>;
}

export interface StatusItemsResponse<T = any> extends ItemsResponse<T> {
  status_request: string;
}

export interface IdResponse extends ApiResponse {
  response: { id: number };
}

export interface ErrorResponseInterface {
  stringCode: number;
  message: string;
  relatedField: any;
}

export interface ApiParamsInterface {
  search?: string;
  filter?: { [columnName: string]: any };
  fields?: { [columnName: string]: any };
  order?: { [columnName: string]: any };
  offset?: number;
  limit?: number;
  download?: string;
  urlParams?: { [columnName: string]: any };
  mode?: 'columns'|'status'|string|Array<string>;
  detail?: boolean;
  percent?: boolean;
  countSymbols?: number;
}

export class ApiParamsModel implements ApiParamsInterface {
  filter: { [columnName: string]: any };
  fields: { [columnName: string]: any };
  order: { [columnName: string]: any };
  offset: number;
  limit: number;
  search: string;
  download: string;
  mode: 'columns'|'status';
  detail: boolean;
  percent: boolean;
  countSymbols: number;

  constructor(o: ApiParamsInterface) {
    constructByInterface(o, this);
  }

  get forApi(): any {
    const params = {};
    if (this.filter && Object.keys(this.filter).length === 0) {
      delete this.filter;
    }

    if (this.search && this.search.length <= 0 || this.search === '') {
      delete this.search;
    }
    Object.keys(this).forEach(key => {
      if (key === 'filter') {
        clearNullsDeep(this.filter);
      }
      if (!_.isNil(this[key])) {
        if (typeof this[key] === 'object') {
          params[key] = JSON.stringify(this[key]);
        } else {
          params[key] = this[key];
        }
      }
    });
    return params;
  }

  get searchParams(): any {
    const params = new URLSearchParams();
    const obj = this.forApi;
    Object.keys(obj).forEach(key => params.append(key, obj[key]));
    return params;
  }

}
