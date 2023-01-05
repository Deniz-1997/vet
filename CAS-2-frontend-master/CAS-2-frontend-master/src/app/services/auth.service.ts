import {Injectable} from '@angular/core';
import {BehaviorSubject, Observable, Subject} from 'rxjs';
import {HttpClient} from '@angular/common/http';
import {Urls} from '../common/urls';
import {prepareHttpParams} from '../utils/prepare-http-params';
import {AccountPasswordRequestInterface} from '../interfaces/account-password.request';
import {select, Store} from '@ngrx/store';
import {PutPasswordRecoveryInterface} from '../interfaces/password-recovery.interface';
import {RestoreCheckCodeRequestInterface} from '../interfaces/restore-check-code.request';
import {environment} from '../../environments/environment';
import {ApiResponse} from '../api/api-connector/api-connector.models';
import {GetTokenParamsInterface, TokenAuthInterface, UserAuthInterface, UserAuthModel} from '../api/auth/auth.models';
import {AuthState} from '../api/auth/auth.reducer';
import {getUser} from '../api/auth/auth.selectors';


@Injectable({
  providedIn: 'root'
})
export class AuthService {

  user$ = new BehaviorSubject(new UserAuthModel());
  userGroupAdmin$ = new BehaviorSubject(false);
  userId$ = new Subject<number>();

  constructor(
    private http: HttpClient,
    private store: Store<AuthState>,
  ) {
    store.pipe(select(getUser)).subscribe(this.user$);
    this.user$.subscribe((user: UserAuthModel) => {
        if (user) {
          const iaAdmin = user.groups.some(item => item.id === 1);
          this.userGroupAdmin$ = new BehaviorSubject(iaAdmin);
          this.userId$.next(user.user.id);
        }
      }
    );
  }

  public permissions(role: any): boolean {
    const user = this.user$.getValue();
    if (user && user.user && user.user['roles']) {
      return user.user['roles'].indexOf(role) > -1;
    } else {
      return false;
    }
  }

  public inGroup(group: any): boolean {
    const user = this.user$.getValue();
    if (user && user['groups']) {
      return !!user['groups'].find(n => n.code === group);
    } else {
      return false;
    }
  }

  getAuthToken(params: GetTokenParamsInterface): Observable<TokenAuthInterface | ApiResponse> {
    return this.http.post<TokenAuthInterface | ApiResponse>(Urls.apiOAuthToken, prepareHttpParams({
      grant_type: 'password',
      scope: 'default',
      ...(environment.credentials || {}),
      ...params,
    }));
  }

  getAccountUser(): Observable<ApiResponse<UserAuthInterface>> {
    return this.http.get<ApiResponse>(Urls.apiAccountUser);
  }

  postAccountLogout(): Observable<ApiResponse> {
    return this.http.post<ApiResponse>(Urls.apiAccountLogout, null);
  }

  postAccountPassword(params: AccountPasswordRequestInterface): Observable<ApiResponse> {
    return this.http.post<ApiResponse>(Urls.apiAccountPassword, prepareHttpParams(params));
  }

  getAccountPasswordRecovery(recipient: string): Observable<ApiResponse> {
    return this.http.get<ApiResponse>(
      Urls.apiAccountPasswordRecovery,
      {params: prepareHttpParams({recipient: recipient})}
    );
  }

  putAccountPasswordRecovery(params: PutPasswordRecoveryInterface): Observable<ApiResponse> {
    return this.http.put<ApiResponse>(Urls.apiAccountPasswordRecovery, params);
  }

  postCheckCode(params: RestoreCheckCodeRequestInterface): Observable<ApiResponse> {
    return this.http.post<ApiResponse>(Urls.apiRestoreCheckCode, params);
  }
}
