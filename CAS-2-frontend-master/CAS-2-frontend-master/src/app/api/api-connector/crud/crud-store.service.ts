import { Injectable, Optional } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { arrayToKeys } from '../api-connector.utils';
import { CrudStoreInterface, CrudTypeUnit, CrudTypeUnitInterface, initial } from './crud.config';
import { CrudActions, CrudActionTypes } from './crud.actions';

export class CrudStoreServiceConfig {
  types: {[type: string]: CrudTypeUnitInterface};
}

export interface CrudState {
  [key: string]: CrudStoreInterface;
}

@Injectable()
export class CrudStoreService {

  readonly config: {[type: string]: CrudTypeUnit} = {};
  readonly initialState: CrudState = {};

  constructor(
    private http: HttpClient,
    @Optional() config: CrudStoreServiceConfig
  ) {
    if (config) {
      Object.keys(config.types).forEach(type => {
        this.config[type] = new CrudTypeUnit(config.types[type]);
        this.initialState[type] = initial;
      });
    }
  }

  createReducer(): any {
    let data: any;
    let dataIds: any;
    return (state = this.initialState, action: CrudActions): CrudState => {
      switch (action.type) {
        case CrudActionTypes.LoadMatchesAction:
          return {
            ...state,
            [action.payload.type]: {
              ...state[action.payload.type],
              matchesLoading: true,
            }
          };
        case CrudActionTypes.CompleteMatchesAction:
          return {
            ...state,
            [action.payload.type]: {
              ...state[action.payload.type],
              matchesLoading: false,
              totalCount: action.payload.params ? action.payload.params.totalCount : 0,
              matches: action.payload.params ? action.payload.params.items : [],
              loaded: action.payload.hasOwnProperty('loaded') ?
                action.payload.loaded :
                (state[action.payload.type].loaded || !!action.payload.params),
              columns: (action.payload.params && action.payload.params.columns) ? action.payload.params.columns : [],
            }
          };
        case CrudActionTypes.LoadGetListAction:
          return {
            ...state,
            [action.payload.type]: {
              ...state[action.payload.type],
              getListLoading: true,
              data: {},
              dataIds: [],
            }
          };
        case CrudActionTypes.CompleteGetListAction:
          return {
            ...state,
            [action.payload.type]: {
              ...state[action.payload.type],
              getListLoading: false,
              totalCount: action.payload.params ? action.payload.params.totalCount : 0,
              data: action.payload.params ? arrayToKeys(
                action.payload.params.items,
                this.config[action.payload.type].idProp,
                this.config[action.payload.type].model,
              ) : {},
              dataIds: action.payload.params ? [
                ...action.payload.params.items
                  .map(item => item[this.config[action.payload.type].idProp])
              ] : [],
              loaded: action.payload.hasOwnProperty('loaded') ? action.payload.loaded : !!action.payload.params,
              columns: (action.payload.params && action.payload.params.columns) ? action.payload.params.columns : [],
            }
          };
        case CrudActionTypes.LoadAppendListAction:
          return {
            ...state,
            [action.payload.type]: {
              ...state[action.payload.type],
              appendListLoading: true,
            }
          };
        case CrudActionTypes.CompleteAppendListAction:
          if (action.payload.params) {
            dataIds = [];
            action.payload.params.items
              .forEach(
                item => !state[action.payload.type].data[item.id] &&
                  dataIds.push(item[this.config[action.payload.type].idProp])
              );
            return {
              ...state,
              [action.payload.type]: {
                ...state[action.payload.type],
                appendListLoading: false,
                totalCount: action.payload.params.totalCount,
                data: {
                  ...state[action.payload.type].data,
                  ...arrayToKeys(
                    action.payload.params.items,
                    this.config[action.payload.type].idProp,
                    this.config[action.payload.type].model,
                  )
                },
                dataIds: [
                  ...state[action.payload.type].dataIds,
                  ...dataIds
                ],
                loaded: action.payload.hasOwnProperty('loaded') ?
                  action.payload.loaded :
                  (state[action.payload.type].loaded || !!action.payload.params),
                columns: (action.payload.params && action.payload.params.columns) ? action.payload.params.columns : [],
              }
            };
          }
          return {
            ...state,
            [action.payload.type]: {
              ...state[action.payload.type],
              appendListLoading: false
            }
          };
        case CrudActionTypes.LoadGetAction:
          return {
            ...state,
            [action.payload.type]: {
              ...state[action.payload.type],
              getLoading: true,
            }
          };
        case CrudActionTypes.CompleteGetAction:
          data = state[action.payload.type].data || {};
          dataIds = state[action.payload.type].dataIds || [];
          if (action.payload.params) {
            if (!data[action.payload.params[this.config[action.payload.type].idProp]]) {
              dataIds = [...dataIds, action.payload.params[this.config[action.payload.type].idProp]];
            }
            data = {
              ...data,
              [action.payload.params[this.config[action.payload.type].idProp]]: action.payload.params
            };
          }
          return {
            ...state,
            [action.payload.type]: {
              ...state[action.payload.type],
              getLoading: false,
              data: data,
              dataIds: dataIds,
              loaded: action.payload.hasOwnProperty('loaded') ?
                action.payload.loaded :
                (state[action.payload.type].loaded || !!action.payload.params),
            }
          };
        case CrudActionTypes.LoadPatchAction:
          return {
            ...state,
            [action.payload.type]: {
              ...state[action.payload.type],
              patchLoading: true,
            }
          };
        case CrudActionTypes.CompletePatchAction:
          data = state[action.payload.type].data || {};
          if (action.payload.params) {
            data = {
              ...data,
              [action.payload.params[this.config[action.payload.type].idProp]]: action.payload.params
            };
          }
          return {
            ...state,
            [action.payload.type]: {
              ...state[action.payload.type],
              patchLoading: false,
              data: data,
              loaded: action.payload.hasOwnProperty('loaded') ?
                action.payload.loaded :
                (state[action.payload.type].loaded || !!action.payload.params),
            }
          };
        case CrudActionTypes.LoadCreateAction:
          return {
            ...state,
            [action.payload.type]: {
              ...state[action.payload.type],
              postLoading: true,
            }
          };
        case CrudActionTypes.CompleteCreateAction:
          data = state[action.payload.type].data || {};
          dataIds = state[action.payload.type].dataIds || [];
          if (action.payload.params) {
            dataIds = [...dataIds, action.payload.params[this.config[action.payload.type].idProp]];
            data = {
              ...data,
              [action.payload.params[this.config[action.payload.type].idProp]]: action.payload.params
            };
          }
          return {
            ...state,
            [action.payload.type]: {
              ...state[action.payload.type],
              postLoading: false,
              data: data,
              dataIds: dataIds,
              loaded: action.payload.hasOwnProperty('loaded') ?
                action.payload.loaded :
                (state[action.payload.type].loaded || !!action.payload.params),
            }
          };
        case CrudActionTypes.LoadDeleteAction:
          return {
            ...state,
            [action.payload.type]: {
              ...state[action.payload.type],
              deleteLoading: true,
            }
          };
        case CrudActionTypes.CompleteDeleteAction:
          if (!action.payload.params) {
            return {
              ...state,
              [action.payload.type]: {
                ...state[action.payload.type],
                deleteLoading: false,
              }
            };
          }
          dataIds = state[action.payload.type].dataIds;
          if (state[action.payload.type].data[action.payload.params.id]) {
            delete state[action.payload.type].data[action.payload.params.id];
            dataIds = dataIds.filter(id => id !== action.payload.params.id);
          }
          return {
            ...state,
            [action.payload.type]: {
              ...state[action.payload.type],
              deleteLoading: false,
              dataIds: dataIds,
            }
          };
        default:
          return state;
      }
    };
  }
}
