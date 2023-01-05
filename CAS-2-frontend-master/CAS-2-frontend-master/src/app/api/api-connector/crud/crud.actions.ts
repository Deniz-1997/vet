import { Action } from '@ngrx/store';
import { CrudDataInterface, CrudDataType, CrudParams } from './crud.config';
import { ItemsResponseInterface, ApiParamsInterface } from '../api-connector.models';

export enum CrudActionTypes {
  LoadCreateAction = '[Crud] Load Create',
  LoadGetAction = '[Crud] Load Get',
  LoadPatchAction = '[Crud] Load Patch',
  LoadDeleteAction = '[Crud] Load Delete',
  LoadGetListAction = '[Crud] Load GetList',
  LoadAppendListAction = '[Crud] Load AppendList',
  CompleteGetListAction = '[Crud] Complete GetList',
  CompleteAppendListAction = '[Crud] Complete AppendList',
  CompleteCreateAction = '[Crud] Complete Create',
  CompleteGetAction = '[Crud] Complete Get',
  CompletePatchAction = '[Crud] Complete Patch',
  CompleteDeleteAction = '[Crud] Complete Delete',
  LoadMatchesAction = '[Crud] Load Matches',
  CompleteMatchesAction = '[Crud] Complete Matches',
}

export class LoadMatchesAction implements Action {
  readonly type = CrudActionTypes.LoadMatchesAction;
  constructor(readonly payload: CrudParams<ApiParamsInterface>) {}
}
export class CompleteMatchesAction implements Action {
  readonly type = CrudActionTypes.CompleteMatchesAction;
  constructor(readonly payload: CrudParams<ItemsResponseInterface<CrudDataType>>) {}
}

export class LoadGetListAction implements Action {
  readonly type = CrudActionTypes.LoadGetListAction;
  constructor(readonly payload: CrudParams<ApiParamsInterface>) {}
}
export class CompleteGetListAction implements Action {
  readonly type = CrudActionTypes.CompleteGetListAction;
  constructor(readonly payload: CrudParams<ItemsResponseInterface<CrudDataType>>) {}
}

export class LoadAppendListAction implements Action {
  readonly type = CrudActionTypes.LoadAppendListAction;
  constructor(readonly payload: CrudParams<ApiParamsInterface>) {}
}
export class CompleteAppendListAction implements Action {
  readonly type = CrudActionTypes.CompleteAppendListAction;
  constructor(readonly payload: CrudParams<ItemsResponseInterface<CrudDataType>>) {}
}

export class LoadGetAction implements Action {
  readonly type = CrudActionTypes.LoadGetAction;
  constructor(readonly payload: CrudParams<CrudDataType|string>) {}
}
export class CompleteGetAction implements Action {
  readonly type = CrudActionTypes.CompleteGetAction;
  constructor(readonly payload: CrudParams<CrudDataType>) {}
}

export class LoadCreateAction implements Action {
  readonly type = CrudActionTypes.LoadCreateAction;
  constructor(readonly payload: CrudParams<CrudDataInterface>) {}
}
export class CompleteCreateAction implements Action {
  readonly type = CrudActionTypes.CompleteCreateAction;
  constructor(readonly payload: CrudParams<{id: number}>) {}
}

export class LoadPatchAction implements Action {
  readonly type = CrudActionTypes.LoadPatchAction;
  constructor(readonly payload: CrudParams<CrudDataInterface>) {}
}
export class CompletePatchAction implements Action {
  readonly type = CrudActionTypes.CompletePatchAction;
  constructor(readonly payload: CrudParams<CrudDataType>) {}
}

export class LoadDeleteAction implements Action {
  readonly type = CrudActionTypes.LoadDeleteAction;
  constructor(readonly payload: CrudParams<{id: number}>) {}
}
export class CompleteDeleteAction implements Action {
  readonly type = CrudActionTypes.CompleteDeleteAction;
  constructor(readonly payload: CrudParams<{id: number}>) {}
}



export type CrudActions = LoadGetListAction | CompleteGetListAction |
  LoadGetAction | CompleteGetAction |
  LoadCreateAction | CompleteCreateAction |
  LoadPatchAction | CompletePatchAction |
  LoadDeleteAction | CompleteDeleteAction |
  LoadMatchesAction | CompleteMatchesAction |
  LoadAppendListAction | CompleteAppendListAction;
