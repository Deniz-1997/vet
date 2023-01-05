import { Injectable, Optional } from '@angular/core';
import { Store } from '@ngrx/store';
import { HttpClient } from '@angular/common/http';
import { AuthState } from './auth.reducer';
import {
  AccountPasswordRequestInterface,
  GetTokenParamsInterface,
  PutPasswordRecoveryInterface, RestoreCheckCodeRequestInterface,
  TokenAuthInterface,
  UserAuthInterface
} from './auth.models';
import { Observable } from 'rxjs';
import { AuthConfig } from './auth.config';
import {ApiParamsModel, ApiResponse} from '../api-connector/api-connector.models';
import {prepareHttpParams} from '../api-connector/api-connector.utils';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  readonly credentials = {
    client_id: '',
    client_secret: '',
  };
  readonly urls = {
    api: '/api',
    apiOAuthToken: '/api/oauth/v2/token/',
    apiAccountUser: '/api/account/user/',
    apiAccountLogout: '/api/account/logout/',
    apiAccountPassword: '/api/account/password/',
    apiAccountPasswordRecovery: '/api/account/password-recovery/',
    apiRestoreCheckCode: '/api/account/check-confirmation-code/',
  };

  readonly userParams = {
    fields: {
      user: ['id', 'username', 'email', 'plainPassword', 'name', 'surname', 'patronymic', 'phoneNumber', 'groups', 'roles'],
      groups: ['id', 'code', 'name'],
    }
  };
  readonly authUrl: string = '/auth';
  readonly  userKey: string = 'puzUser';

  constructor(
    private http: HttpClient,
    private store: Store<AuthState>,
    @Optional() private authConfig: AuthConfig,
  ) {
    if (authConfig) {
      this.credentials = authConfig.credentials;
      Object.assign(this.urls, authConfig.urls);
      this.authUrl = authConfig.authUrl;
      this.userKey = authConfig.userKey;
    }
  }

  getAuthUrl(): string {
    return this.authUrl;
  }
  getApiUrl(): string| Array<string> {
    return this.urls.api;
  }
  getUserKey(): string {
    return this.userKey;
  }

  getAuthToken(params: GetTokenParamsInterface): Observable<TokenAuthInterface | ApiResponse> {
    return this.http.post<TokenAuthInterface | ApiResponse>(this.urls.apiOAuthToken, prepareHttpParams({
      grant_type: 'password',
      scope: 'default',
      ...this.credentials,
      ...params,
    }));
  }

  getAccountUser(): Observable<ApiResponse<UserAuthInterface>> {
    return this.http.get<ApiResponse>(this.urls.apiAccountUser, {params: prepareHttpParams(new ApiParamsModel(this.userParams).forApi)});
  }

  postAccountLogout(): Observable<ApiResponse> {
    return this.http.post<ApiResponse>(this.urls.apiAccountLogout, null);
  }

  postAccountPassword(params: AccountPasswordRequestInterface): Observable<ApiResponse> {
    return this.http.post<ApiResponse>(this.urls.apiAccountPassword, prepareHttpParams(params));
  }

  getAccountPasswordRecovery(recipient: string): Observable<ApiResponse> {
    return this.http.get<ApiResponse>(
      this.urls.apiAccountPasswordRecovery,
      {params: prepareHttpParams({recipient: recipient})}
    );
  }

  putAccountPasswordRecovery(params: PutPasswordRecoveryInterface): Observable<ApiResponse> {
    return this.http.put<ApiResponse>(this.urls.apiAccountPasswordRecovery, params);
  }

  postCheckCode(params: RestoreCheckCodeRequestInterface): Observable<ApiResponse> {
    return this.http.post<ApiResponse>(this.urls.apiRestoreCheckCode, params);
  }
}
