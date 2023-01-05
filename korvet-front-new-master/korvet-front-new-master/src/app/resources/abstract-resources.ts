import {Urls} from '../common/urls';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';
import {Injectable} from '@angular/core';
import {ApiResponse} from '../api/api-connector/api-connector.models';

@Injectable({
  providedIn: 'root'
})
export abstract class AbstractResources {

  constructor(
    protected http: HttpClient
  ) {
  }

  private _url: string = Urls.api;

  get url(): string {
    return this._url;
  }

  set url(value: string) {
    this._url = value;
  }

  execute(url: string, type: string, params?: object): Observable<ApiResponse> {
    return this.http[type]<ApiResponse>(url, params);
  }

  get(params?: object): Observable<ApiResponse> {
    return this.http.get<ApiResponse>(this.url, params);
  }

  post(params: object): Observable<ApiResponse> {
    return this.http.post<ApiResponse>(this.url, params);
  }

  put(params: object): Observable<ApiResponse> {
    return this.http.put<ApiResponse>(this.url, params);
  }

  delete(params: object): Observable<ApiResponse> {
    return this.http.delete<ApiResponse>(this.url, params);
  }
}
