import { Injectable } from '@angular/core';
import { HttpEvent, HttpHandler, HttpInterceptor, HttpRequest } from '@angular/common/http';
import { select, Store } from '@ngrx/store';
import { catchError, filter, switchMap, take } from 'rxjs/operators';
import { Observable, throwError } from 'rxjs';
import { AuthState } from './auth.reducer';
import { AuthTokenService } from './auth-token.service';
import { LogoutAction, RefreshTokenAction } from './auth.actions';
import { getToken } from './auth.selectors';
import { AuthService } from './auth.service';

@Injectable()
export class AuthInterceptor implements HttpInterceptor {

  constructor(
    private store: Store<AuthState>,
    private token: AuthTokenService,
    private service: AuthService,
  ) {}

  intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    let result = next.handle(req);
    const token = this.token.get();
    const url = this.service.getApiUrl();
    if ((url instanceof Array ? url.some(u => this.testUrl(req.url, u)) : this.testUrl(req.url, url)) && !!token) {
      result = next.handle(req.clone({setHeaders: {'Authorization': 'Bearer ' + token.access_token}}));
    }
    return result
      .pipe(
        catchError(err => {
          if (err.status === 401 || err.status === 403 || err.httpResponseCode === 401 || err.httpResponseCode === 403) {
            if (this.token.check()) {
              const lastToken = this.token.get();
              this.store.dispatch(new RefreshTokenAction());
              return this.store.pipe(
                select(getToken),
                filter(t => t && t['access_token'] && t['access_token'] !== lastToken['access_token']),
                take(1),
                switchMap(newToken => result = next.handle(
                  req.clone({
                    setHeaders: {'Authorization': 'Bearer ' + newToken.access_token}
                  })
                ))
              );
            } else {
              this.store.dispatch(new LogoutAction());
              return;
            }
          }
          return throwError(err);
        })
      );
  }

  private testUrl(url: string, testUrl: string): boolean {
    return new RegExp('^' + testUrl).test(url);
  }
}
