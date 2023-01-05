import { Injectable } from '@angular/core';
import { Actions, Effect, ofType, ROOT_EFFECTS_INIT } from '@ngrx/effects';
import {
  AuthActionTypes,
  GetRestoreAction,
  GetTokenAction,
  GetUserAction,
  LogoutAction, RemoveErrorAction,
  RequestAccountPasswordRecovery,
  RequestPasswordChange,
  RequestRestoreCheckCodeAction,
  ResponseAccountPasswordRecovery,
  ResponsePasswordChange,
  ResponseRestoreCheckCodeAction, SetErrorAction,
  SetRestoreLoginAction,
  SetTokenAction,
  SetUserAction
} from './auth.actions';
import { catchError, exhaustMap, finalize, map, switchMap, tap } from 'rxjs/operators';
import { concat, of } from 'rxjs';
import { Store } from '@ngrx/store';
import { AuthService } from './auth.service';
import { AuthState } from './auth.reducer';
import { AuthTokenService } from './auth-token.service';
import { TokenAuthInterface, TokenAuthModel, UserAuthModel } from './auth.models';
import { Router } from '@angular/router';


@Injectable()
export class AuthEffects {

  constructor(
    private actions$: Actions,
    private service: AuthService,
    private store: Store<AuthState>,
    private token: AuthTokenService,
    private router: Router,
  ) {
  }

  @Effect({dispatch: false})
  init$ = this.actions$.pipe(
    ofType(ROOT_EFFECTS_INIT),
    tap(() => {
      if (this.token.check()) {
        this.store.dispatch(new SetTokenAction({
          params: new TokenAuthModel(this.token.get()),
        }));
      }
    })
  );

  @Effect()
  getToken$ = this.actions$.pipe(
    ofType(AuthActionTypes.GetTokenAction),
    exhaustMap((data: GetTokenAction) => {
      this.store.dispatch(new RemoveErrorAction({type: data.type}));
      return this.service.getAuthToken(data.payload.params)
        .pipe(
          map(res => new SetTokenAction({
            params: new TokenAuthModel(res as TokenAuthInterface),
            storage: data.payload.storage
          })),
          catchError(err => concat(of(new SetErrorAction({
            type: data.type,
            error: err['errors'],
          })), of(data.payload.params.grant_type === 'refresh_token' ? new LogoutAction() : new SetTokenAction(null))))
        );
    })
  );

  @Effect({dispatch: false})
  setToken$ = this.actions$.pipe(
    ofType(AuthActionTypes.SetTokenAction),
    tap(
      (data: SetTokenAction) => {
        data.payload ?
          this.token.set(data.payload.params, data.payload.storage) :
          this.token.remove();
      }
    )
  );

  @Effect()
  refreshToken$ = this.actions$.pipe(
    ofType(AuthActionTypes.RefreshTokenAction),
    map(() => {
      if (this.token.check()) {
        const token = this.token.get();
        if (token['refresh_token']) {
          return new GetTokenAction({
            params: {
              grant_type: 'refresh_token',
              refresh_token: token['refresh_token']
            },
          });
        }
      }
      return new LogoutAction();
    })
  );

  @Effect()
  restore$ = this.actions$.pipe(
    ofType(AuthActionTypes.GetRestoreAction),
    switchMap((data: GetRestoreAction) => {
      return this.service.getAccountPasswordRecovery(data.payload)
        .pipe(
          map(res => new SetRestoreLoginAction(res.status ? data.payload : null)),
          catchError(err => of(new SetRestoreLoginAction(null)))
        );
    })
  );

  @Effect()
  getUser$ = this.actions$.pipe(
    ofType(AuthActionTypes.GetUserAction),
    switchMap((data: GetUserAction) => {
      this.store.dispatch(new RemoveErrorAction({type: data.type}));
      return this.service.getAccountUser()
        .pipe(
          map(res => new SetUserAction(new UserAuthModel(res.response))),
          catchError(err => of(new SetErrorAction({
            type: data.type,
            error: err['errors'],
          })))
        );
    })
  );

  @Effect({dispatch: false})
  getLogout$ = this.actions$.pipe(
    ofType(AuthActionTypes.GetLogoutAction),
    switchMap(
      () => this.service.postAccountLogout()
        .pipe(finalize(() => this.store.dispatch(new LogoutAction())))
    )
  );

  @Effect({dispatch: false})
  logout$ = this.actions$.pipe(
    ofType(AuthActionTypes.LogoutAction),
    tap(res => {
      this.token.remove();
      // window.location.replace(this.service.getAuthUrl());
      this.token.setLastRoute();
      this.router.navigate(['/auth']).then();
    })
  );

  @Effect()
  passwordChange$ = this.actions$.pipe(
    ofType(AuthActionTypes.RequestPasswordChange),
    switchMap((data: RequestPasswordChange) => {
      return this.service.postAccountPassword(data.payload)
        .pipe(
          map(res => new ResponsePasswordChange(res.status)),
          catchError(err => of(new ResponsePasswordChange(false)))
        );
    }),
  );

  @Effect()
  restoreCheckCode$ = this.actions$.pipe(
    ofType(AuthActionTypes.RequestRestoreCheckCodeAction),
    switchMap((data: RequestRestoreCheckCodeAction) => {
      return this.service.postCheckCode(data.payload)
        .pipe(
          map(res => new ResponseRestoreCheckCodeAction(res.status)),
          catchError(err => of(new ResponseRestoreCheckCodeAction(null)))
        );
    }),
  );

  @Effect()
  accountPasswordRecovery$ = this.actions$.pipe(
    ofType(AuthActionTypes.RequestAccountPasswordRecovery),
    switchMap((data: RequestAccountPasswordRecovery) => {
      return this.service.putAccountPasswordRecovery(data.payload)
        .pipe(
          map(res => new ResponsePasswordChange(res.status)),
          catchError(err => of(new ResponseAccountPasswordRecovery(null)))
        );
    }),
  );
}
