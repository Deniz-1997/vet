import {createSelector} from '@ngrx/store';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {getCrudState} from 'src/app/api/api-connector/crud/crud.selectors';
import {CrudType} from 'src/app/common/crud-types';

export const getCrudPets = createSelector(getCrudState, (state: CrudState) => state[CrudType.Pet]);
export const getCrudPetsData = createSelector(getCrudState,
  (state: CrudState) => state[CrudType.Pet].dataIds.map(id => state[CrudType.Pet].data[id]));
export const getCrudPetsStore = createSelector(getCrudState, (state: CrudState) => state[CrudType.Pet].data);
export const getCrudEventsStore = createSelector(getCrudState, (state: CrudState) => state[CrudType.Event].data);
export const getCrudEventsData = createSelector(getCrudState,
  (state: CrudState) => state[CrudType.Event].dataIds.map(id => state[CrudType.Event].data[id]));
export const getReferencePetTypeStore = createSelector(getCrudState,
  (state: CrudState) => state[CrudType.ReferencePetType].dataIds.map(id => state[CrudType.ReferencePetType].data[id]));
export const getReferenceBreedStore = createSelector(getCrudState,
  (state: CrudState) => state[CrudType.ReferenceBreed].dataIds.map(id => state[CrudType.ReferenceBreed].data[id]));
export const getCrudAppointmentsStore = createSelector(getCrudState, (state: CrudState) => state[CrudType.Appointment].data);
export const getCrudAppointmentsData = createSelector(getCrudState,
  (state: CrudState) => state[CrudType.Appointment].dataIds.map(id => state[CrudType.Appointment].data[id]));
export const getReferencePetIdentifierType = createSelector(getCrudState,
  (state: CrudState) => state[CrudType.ReferencePetIdentifierType].dataIds.map(id => state[CrudType.ReferencePetIdentifierType].data[id]));
export const getReferenceEventTypeStore = createSelector(getCrudState,
  (state: CrudState) => state[CrudType.ReferenceEventType].dataIds.map(id => state[CrudType.ReferenceEventType].data[id]));
export const getTemplateReferenceStore = createSelector(getCrudState,
  (state: CrudState) => state[CrudType.TemplateReference].dataIds.map(id => state[CrudType.TemplateReference].data[id]));
export const getReferenceUnitItem = createSelector(getCrudState,
  (state: CrudState) => state[CrudType.ReferenceUnit].dataIds.map(id => state[CrudType.ReferenceUnit].data[id]));
