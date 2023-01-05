import {Injectable} from '@angular/core';
import {AbstractResources} from '../abstract-resources';
import {Observable} from 'rxjs';
import {prepareHttpParams} from '../../utils/prepare-http-params';
import {ApiParamsInterface, ApiResponse, ApiParamsModel} from 'src/app/api/api-connector/api-connector.models';

@Injectable({
  providedIn: 'root'
})
export class PetsResources extends AbstractResources {
  private urlApi: string = this.url + 'pet/:id/';

  get(params: ApiParamsInterface, model?: object): Observable<ApiResponse> {
    /*const pUrl = new HttpParams();
    for (const key in params) {
      pUrl.set(key, params[key]);
    }*/
    let url: string = this.urlApi;
    url = url.replace(':id/', model && model.hasOwnProperty('id') ? params['id'] + '/' : '');
    /*url += pUrl ? '?' + pUrl.toString() : '';*/
    return this.execute(url, 'get', {params: prepareHttpParams(new ApiParamsModel(params).forApi)});
  }
}
