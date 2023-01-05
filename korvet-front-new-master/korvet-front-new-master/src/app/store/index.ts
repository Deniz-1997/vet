import {ActionReducer, ActionReducerMap, MetaReducer} from '@ngrx/store';
// import * as auth from './auth';
import * as error from './error';
// import * as crud from './crud';
import {environment} from '../../environments/environment';

export interface AppState {
  // auth: auth.AuthState;
  error: error.ErrorState;
  // crud: crud.CrudState;
}

export const reducers: ActionReducerMap<AppState> = {
  // auth: auth.authReducer,
  error: error.errorReducer,
  // crud: crud.crudReducer,
};

export const effects = [
  // auth.AuthEffects,
  error.ErrorEffects,
  // crud.CrudEffects,
];

export function logger(
  reducer: ActionReducer<AppState>
): ActionReducer<AppState> {
  return function (state: AppState, action: any): AppState {
    // console.log(action, state);
    return reducer(state, action);
  };
}


export const metaReducers: MetaReducer<AppState>[] = !environment.production ? [] : [];
