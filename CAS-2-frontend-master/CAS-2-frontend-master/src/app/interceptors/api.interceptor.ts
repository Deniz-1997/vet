import {Injectable} from '@angular/core';
import {HttpEvent, HttpHandler, HttpInterceptor, HttpRequest} from '@angular/common/http';
import {Store} from '@ngrx/store';
import {catchError} from 'rxjs/operators';
import {Observable, throwError} from 'rxjs';
import {Urls} from '../common/urls';
import {NotifyService} from '../services/notify.service';
import {NoAccessService} from '../services/no-access.service';
import {SnackBarService} from '../services/snack-bar.service';
import {AuthState} from '../api/auth/auth.reducer';
import {AuthTokenService} from '../api/auth/auth-token.service';
import {ApiResponse} from '../api/api-connector/api-connector.models';

@Injectable()
export class ApiInterceptor implements HttpInterceptor {

  constructor(
    private store: Store<AuthState>,
    private token: AuthTokenService,
    private notify: NotifyService,
    private snackBar: SnackBarService,
    private noAccessService: NoAccessService,
  ) {
  }

  intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    if (localStorage.getItem('switchUser')) {
      req = req.clone({
        setHeaders: {
          'x-switch-user': localStorage.getItem('switchUser'),
        }
      });
    }

    let result = next.handle(req);
    const token = this.token.get();
    if (new RegExp('^' + Urls.api).test(req.url) && !!token) {
      result = next.handle(req.clone({setHeaders: {Authorization: 'Bearer ' + token.access_token}}));
    }
    return result
      .pipe(
        catchError(err => {
          if (err.status === 403) {
            this.noAccessService.noAccess.next(true);
          }
          if (err.status === 400 || err.status === 500) {
            this.snackBar.handleErrors(err.error['errors']);
            return throwError(err.error);
          }

          let errorMessage;

          if (err.error && err.error.errors[0] && err.error.errors[0].message) {
            errorMessage = err.error.errors[0].message;
          } else {
            errorMessage = err.message;
          }

          return throwError({
            errors: [{
              stringCode: null,
              message: errorMessage,
              relatedField: null,
            }],
            httpResponseCode: err.status,
            status: false,
          } as ApiResponse);
        })
      );
  }

}
