import {ErrorActions, ErrorActionTypes} from './error.actions';
import {ErrorStore} from '../../common/error-type';


export interface ErrorState {
  error: { [key: string]: ErrorStore };
}

export const initialState: ErrorState = {
  error: {},
};

export function errorReducer(state = initialState, action: ErrorActions): ErrorState {
  switch (action.type) {
    case ErrorActionTypes.RemoveErrorActon:
      return {
        error: {
          ...state.error,
          [action.payload]: null,
        }
      };
    case ErrorActionTypes.SetErrorActon:
      return {
        error: {
          ...state.error,
          [action.payload.type]: {message: action.payload.message}
        }
      };
    case ErrorActionTypes.ClearErrorAction:
      return {
        ...initialState
      };
    default:
      return state;
  }
}
