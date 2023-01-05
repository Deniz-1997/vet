import { createFeatureSelector, createSelector } from '@ngrx/store';
import { AuthState } from './auth.reducer';

export const getAuthState = createFeatureSelector('auth');
export const getToken = createSelector(getAuthState, (state: AuthState) => state.token);
export const getTokenLoading = createSelector(getAuthState, (state: AuthState) => state.tokenLoading);
export const getUser = createSelector(getAuthState, (state: AuthState) => state.user);
export const getUserLoading = createSelector(getAuthState, (state: AuthState) => state.userLoading);
export const getLogoutLoading = createSelector(getAuthState, (state: AuthState) => state.logoutLoading);
export const getPasswordChangeLoading = createSelector(getAuthState, (state: AuthState) => state.passwordChangeLoading);
export const getPasswordChangeStatus = createSelector(getAuthState, (state: AuthState) => state.passwordChangeStatus);
export const getRestoreRecipient = createSelector(getAuthState, (state: AuthState) => state.restore.recipient);
export const getRestoreStatus = createSelector(getAuthState, (state: AuthState) => state.restore.status);
export const getRestoreLoading = createSelector(getAuthState, (state: AuthState) => state.restoreLoading);

export const getRestoreCheckCodeStatus = createSelector(getAuthState, (state: AuthState) => state.restoreCheckCode);
export const getRestoreCheckCodeLoading = createSelector(getAuthState, (state: AuthState) => state.restoreCheckCodeLoading);

export const getAccountPasswordRecoveryStatus = createSelector(getAuthState, (state: AuthState) => state.accountPasswordRecovery);
export const getAccountPasswordRecoveryLoading = createSelector(getAuthState, (state: AuthState) => state.accountPasswordRecoveryLoading);

export const getAuthErrors = createSelector(getAuthState, (state: AuthState) => state.errors);
