import {HttpParams} from '@angular/common/http';

export function prepareHttpParams(params: Object): HttpParams {
  let httpParams = new HttpParams();
  Object.keys(params).forEach(key => params[key] !== null && (httpParams = httpParams.append(key, params[key])));
  return httpParams;
}
