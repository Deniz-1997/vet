import {createFeatureSelector, createSelector} from '@ngrx/store';
import {ErrorState} from './error.reducer';

export const getErrorState = createFeatureSelector('error');
export const getError = createSelector(getErrorState, (state: ErrorState) => state.error);
