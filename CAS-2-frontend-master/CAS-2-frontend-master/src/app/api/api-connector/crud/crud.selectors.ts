import { createFeatureSelector, createSelector } from '@ngrx/store';
import { CrudParams } from './crud.config';
import { CrudState } from './crud-store.service';

export const getCrudState = createFeatureSelector('crud');
export const getCrud = createSelector(getCrudState, (state: CrudState) => state);
export const getCrudModel = createSelector(getCrudState, (state: CrudState, props: CrudParams) => state[props.type]);
export const getCrudModelData = createSelector(
  getCrudState,
  (state: CrudState, props: CrudParams) => state[props.type].dataIds ? state[props.type].dataIds
    .map(id => state[props.type].data[id]) : []
);
export const getCrudModelColumns = createSelector(getCrudState, (state: CrudState, props: CrudParams) => state[props.type].columns);
export const getCrudModelStore = createSelector(getCrudState, (state: CrudState, props: CrudParams) => state[props.type].data);
export const getCrudModelStoreId = createSelector(
  getCrudState,
  (state: CrudState, props: CrudParams<string | number>) => state[props.type].data[props.params]
);
export const getCrudModelGetListLoading = createSelector(
  getCrudState,
  (state: CrudState, props: CrudParams) => state[props.type].getListLoading
);
export const getCrudModelGetLoading = createSelector(getCrudState, (state: CrudState, props: CrudParams) => state[props.type].getLoading);
export const getCrudModelPatchLoading = createSelector(
  getCrudState,
  (state: CrudState, props: CrudParams) => state[props.type].patchLoading
);
export const getCrudModelCreateLoading = createSelector(
  getCrudState,
  (state: CrudState, props: CrudParams) => state[props.type].postLoading
);
export const getCrudModelDeleteLoading = createSelector(
  getCrudState,
  (state: CrudState, props: CrudParams) => state[props.type].deleteLoading
);
export const getCrudModelAppendListLoading = createSelector(
  getCrudState,
  (state: CrudState, props: CrudParams) => state[props.type].appendListLoading
);
export const getCrudModelCreatePatchLoading = createSelector(
  getCrudState,
  (state: CrudState, props: CrudParams) => (state[props.type].postLoading || state[props.type].patchLoading)
);
export const getCrudModelTotalCount = createSelector(getCrudState, (state: CrudState, props: CrudParams) => state[props.type].totalCount);
export const getCrudModelMatches = createSelector(getCrudState, (state: CrudState, props: CrudParams) => state[props.type].matches);
export const getCrudModelMatchesLoading = createSelector(
  getCrudState,
  (state: CrudState, props: CrudParams) => state[props.type].matchesLoading
);
export const getCrudModelLoaded = createSelector(getCrudState, (state: CrudState, props: CrudParams) => state[props.type].loaded);
