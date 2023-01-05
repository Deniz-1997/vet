import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { ApiParamsInterface, ApiParamsModel, ApiResponse, ItemsResponse } from './api-connector.models';
import { DataType, getHttpData, prepareHttpParams } from './api-connector.utils';

@Injectable()
export class ApiConnectorService {

  constructor(private http: HttpClient) { }

  getList(url: string, params: ApiParamsInterface = {}): Observable<ItemsResponse> {
    return this.http.get<ItemsResponse>(url, {params: prepareHttpParams(new ApiParamsModel(params).forApi)});
  }

  post(url: string, params: any,  fields: ApiParamsInterface = {}, dataType: DataType): Observable<ApiResponse> {
    return this.http.post<ApiResponse>(url, getHttpData(params, dataType || DataType.json),
      {params: prepareHttpParams(new ApiParamsModel(fields).forApi)});
  }

  patch(url: string, params: any, fields: ApiParamsInterface = {}, dataType: DataType): Observable<ApiResponse> {
    return this.http.patch<ApiResponse>(url, getHttpData(params, dataType || DataType.json),
      {params: prepareHttpParams(new ApiParamsModel(fields).forApi)} );
  }

  get(url: string, params: ApiParamsInterface = {}): Observable<ApiResponse> {
    return this.http.get<ApiResponse>(url, {params: prepareHttpParams(new ApiParamsModel(params).forApi)});
  }

  delete(url: string): Observable<ApiResponse> {
    return this.http.delete<ApiResponse>(url);
  }
}
