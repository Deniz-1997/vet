import {Action} from '@ngrx/store';
import {ErrorType} from '../../common/error-type';

export enum ErrorActionTypes {
  SetErrorActon = '[Error] Set Error',
  RemoveErrorActon = '[Error] Remove Error',
  ClearErrorAction = '[Error] Clear Error',
}

export class RemoveErrorActon implements Action {
  readonly type = ErrorActionTypes.RemoveErrorActon;

  constructor(readonly payload: ErrorType) {
  }
}

export class SetErrorActon implements Action {
  readonly type = ErrorActionTypes.SetErrorActon;

  constructor(readonly payload: { type: ErrorType, message: string }) {
  }
}

export class ClearErrorAction implements Action {
  readonly type = ErrorActionTypes.ClearErrorAction;
}

export type ErrorActions = SetErrorActon | RemoveErrorActon | ClearErrorAction;
