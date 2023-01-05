import { Action } from '@ngrx/store';
import { StorageType } from './auth.utils';
import {
  AccountPasswordRequestInterface,
  GetTokenParamsInterface,
  PutPasswordRecoveryInterface, RestoreCheckCodeRequestInterface,
  TokenAuthModel,
  UserAuthModel
} from './auth.models';
import {ErrorResponseInterface} from '../api-connector/api-connector.models';

export enum AuthActionTypes {
  GetTokenAction = '[Auth] Get Token',
  SetTokenAction = '[Auth] Set Token',
  RefreshTokenAction = '[Auth] Refresh Token',
  GetUserAction = '[Auth] Get User',
  SetUserAction = '[Auth] Set User',
  GetLogoutAction = '[Auth] Get Logout',
  RequestPasswordChange = '[Auth] Request PasswordChange',
  ResponsePasswordChange = '[Auth] Response PasswordChange',
  LogoutAction = '[Auth] Logout',
  GetRestoreAction = '[Auth] Get Restore',
  SetRestoreLoginAction = '[Auth] Set RestoreLogin',
  PutRestoreAction = '[Auth] Put Restore',
  SetRestoreAction = '[Auth] Set Restore',
  RequestRestoreCheckCodeAction = '[Auth] Request RestoreCheckCode',
  ResponseRestoreCheckCodeAction = '[Auth] Response RestoreCheckCode',
  RequestAccountPasswordRecovery = '[Auth] Request AccountPasswordRecovery',
  ResponseAccountPasswordRecovery = '[Auth] Response AccountPasswordRecovery',
  SetErrorActon = '[Auth] Set Error',
  RemoveErrorAction = '[Auth] Remove Error',
}

export class GetTokenAction implements Action {
  readonly type = AuthActionTypes.GetTokenAction;

  constructor(readonly payload: { params: GetTokenParamsInterface, storage?: StorageType }) {
  }
}

export class SetTokenAction implements Action {
  readonly type = AuthActionTypes.SetTokenAction;

  constructor(readonly payload: { params: TokenAuthModel, storage?: StorageType }) {
  }
}

export class RefreshTokenAction implements Action {
  readonly type = AuthActionTypes.RefreshTokenAction;
}

export class GetUserAction implements Action {
  readonly type = AuthActionTypes.GetUserAction;
}

export class SetUserAction implements Action {
  readonly type = AuthActionTypes.SetUserAction;

  constructor(readonly payload: UserAuthModel) {
  }
}

export class GetLogoutAction implements Action {
  readonly type = AuthActionTypes.GetLogoutAction;
}

export class LogoutAction implements Action {
  readonly type = AuthActionTypes.LogoutAction;
}

export class RequestPasswordChange implements Action {
  readonly type = AuthActionTypes.RequestPasswordChange;

  constructor(readonly payload: AccountPasswordRequestInterface) {
  }
}

export class ResponsePasswordChange implements Action {
  readonly type = AuthActionTypes.ResponsePasswordChange;

  constructor(readonly payload: boolean) {
  }
}

export class GetRestoreAction implements Action {
  readonly type = AuthActionTypes.GetRestoreAction;

  constructor(readonly payload: string) {
  }
}

export class SetRestoreLoginAction implements Action {
  readonly type = AuthActionTypes.SetRestoreLoginAction;

  constructor(readonly payload: string) {
  }
}

export class PutRestoreAction implements Action {
  readonly type = AuthActionTypes.PutRestoreAction;

  constructor(readonly payload: PutPasswordRecoveryInterface) {
  }
}

export class SetRestoreAction implements Action {
  readonly type = AuthActionTypes.SetRestoreAction;

  constructor(readonly payload: boolean = true) {
  }
}

export class RequestRestoreCheckCodeAction implements Action {
  readonly type = AuthActionTypes.RequestRestoreCheckCodeAction;

  constructor(readonly payload: RestoreCheckCodeRequestInterface) {
  }
}

export class ResponseRestoreCheckCodeAction implements Action {
  readonly type = AuthActionTypes.ResponseRestoreCheckCodeAction;

  constructor(readonly payload: boolean = true) {
  }
}

export class RequestAccountPasswordRecovery implements Action {
  readonly type = AuthActionTypes.RequestAccountPasswordRecovery;

  constructor(readonly payload: PutPasswordRecoveryInterface) {
  }
}

export class ResponseAccountPasswordRecovery implements Action {
  readonly type = AuthActionTypes.ResponseAccountPasswordRecovery;

  constructor(readonly payload: boolean = true) {
  }
}

export class SetErrorAction implements Action {
  readonly type = AuthActionTypes.SetErrorActon;
  constructor(readonly payload: {type: AuthActionTypes|string, error: ErrorResponseInterface|any}) {}
}
export class RemoveErrorAction implements Action {
  readonly type = AuthActionTypes.RemoveErrorAction;
  constructor(readonly payload: {type: AuthActionTypes|string}) {}
}


export type AuthActions = LogoutAction | GetLogoutAction |
  GetTokenAction | SetTokenAction | RefreshTokenAction |
  GetUserAction | SetUserAction |
  RequestPasswordChange | ResponsePasswordChange |
  GetRestoreAction | SetRestoreLoginAction |
  PutRestoreAction | SetRestoreAction | RequestRestoreCheckCodeAction | ResponseRestoreCheckCodeAction |
  RequestAccountPasswordRecovery | ResponseAccountPasswordRecovery |
  SetErrorAction | RemoveErrorAction;
