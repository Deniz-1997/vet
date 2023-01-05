import {ErrorResponseInterface} from '../api-connector/api-connector.models';
import { AuthActions, AuthActionTypes } from './auth.actions';
import { TokenAuthModel, UserAuthModel } from './auth.models';


export interface AuthState {
  token: TokenAuthModel;
  tokenLoading: boolean;
  user: UserAuthModel;
  userLoading: boolean;
  logoutLoading: boolean;
  passwordChangeLoading: boolean;
  passwordChangeStatus: boolean;
  restore: { recipient: string, status: boolean };
  restoreLoading: boolean;
  restoreCheckCode: boolean;
  restoreCheckCodeLoading: boolean;
  accountPasswordRecovery: boolean;
  accountPasswordRecoveryLoading: boolean;
  errors: {[type: string]: Array<ErrorResponseInterface>|any};
}

export const initialState: AuthState = {
  token: null,
  tokenLoading: false,
  user: null,
  userLoading: false,
  logoutLoading: false,
  passwordChangeLoading: false,
  passwordChangeStatus: false,
  restore: {
    recipient: null,
    status: false,
  },
  restoreLoading: false,
  restoreCheckCode: false,
  restoreCheckCodeLoading: false,
  accountPasswordRecovery: false,
  accountPasswordRecoveryLoading: false,
  errors: {},
};

export function authReducer(state: any = initialState, action: AuthActions): AuthState {
  /*Object.assign(state, {
    errors: {
      ...state.errors,
      [action.type]: null,
    },
  });*/
  switch (action.type) {
    case AuthActionTypes.GetTokenAction:
      return {
        ...state,
        tokenLoading: true,
      };
    case AuthActionTypes.SetTokenAction:
      return {
        ...state,
        token: action.payload && action.payload.params,
        tokenLoading: false,
      };
    case AuthActionTypes.GetUserAction:
      return {
        ...state,
        userLoading: true,
      };
    case AuthActionTypes.SetUserAction:
      return {
        ...state,
        user: action.payload,
        userLoading: false,
      };
    case AuthActionTypes.GetLogoutAction:
      return {
        ...state,
        logoutLoading: true,
      };
    case AuthActionTypes.LogoutAction:
      return {...initialState};
    case AuthActionTypes.RequestPasswordChange:
      return {
        ...state,
        passwordChangeLoading: true,
      };
    case AuthActionTypes.ResponsePasswordChange:
      return {
        ...state,
        passwordChangeLoading: false,
        passwordChangeStatus: action.payload,
      };
    case AuthActionTypes.GetRestoreAction:
      return {
        ...state,
        restoreLoading: true,
      };
    case AuthActionTypes.SetRestoreLoginAction:
      return {
        ...state,
        restoreLoading: false,
        restore: {
          status: false,
          recipient: action.payload,
        }
      };
    case AuthActionTypes.PutRestoreAction:
      return {
        ...state,
        restoreLoading: true,
      };
    case AuthActionTypes.SetRestoreAction:
      return {
        ...state,
        restoreLoading: false,
        restore: {
          ...state.restore,
          status: action.payload,
        }

      };
    case AuthActionTypes.RequestRestoreCheckCodeAction:
      return {
        ...state,
        restoreCheckCodeLoading: true,
      };
    case AuthActionTypes.ResponseRestoreCheckCodeAction:
      return {
        ...state,
        restoreCheckCodeLoading: false,
        restoreCheckCode: action.payload
      };
    case AuthActionTypes.RequestAccountPasswordRecovery:
      return {
        ...state,
        accountPasswordRecovery: true,
      };
    case AuthActionTypes.ResponseAccountPasswordRecovery:
      return {
        ...state,
        accountPasswordRecovery: false,
        accountPasswordRecoveryLoading: action.payload
      };
    case AuthActionTypes.SetErrorActon:
      return {
        ...state,
        errors: {
          ...state.errors,
          [action.payload.type]: action.payload.error
        }
      };
    case AuthActionTypes.RemoveErrorAction:
      return {
        ...state,
        errors: {
          ...state.errors,
          [action.payload.type]: null,
        }
      };
    default:
      return state;
  }
}
