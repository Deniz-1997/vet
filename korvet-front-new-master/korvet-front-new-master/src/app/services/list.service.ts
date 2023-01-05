import {Injectable} from '@angular/core';
import {ItemsResponse} from '../api/api-connector/api-connector.models';
import {AbstractResources} from '../resources/abstract-resources';

@Injectable({
  providedIn: 'root'
})
export class ListService {
  items = [];
  public isLoad = false;
  private _title = '';
  private _urlParams = {};
  private _model = {
    filter: {},
    currentFilter: {},
  };
  private showFilter = true;
  private totalCount = 0;
  private _page: {
    offset: number;
    limit: number;
    number: number;
  } = {
    offset: 0,
    limit: 40,
    number: 1
  };
  private _sort = {
    'id': 'desc'
  };
  private $api: any;
  private $apiMethod: string = null;

  constructor() {
  }

  setOptions<T extends AbstractResources>(options: { title: string, api: T }) {
    if (options.api) {
      this.$api = options.api;
    }
    return this;
  }

  getDataList(that) {
    const params = this._urlParams;
    params['offset'] = this._page.offset;
    params['limit'] = this._page.limit;
    params['order'] = this._sort;
    this._page.offset += this._page.limit;
    if (this.isLoad) {
      return false;
    }
    this.isLoad = true;
    return this.$api.get(params).subscribe((data: ItemsResponse) => {
      /*this.items = this.items.concat(data.response.items);*/
      that.items = that.items.concat(data.response.items);
      that.totalCount = this.items.concat(data.response.totalCount);
      this.isLoad = false;
      return data;
    });
  }

  load(that: any) {
    /*this.items = [];*/
    return this.getDataList(that);
  }


}
