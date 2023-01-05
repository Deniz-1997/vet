import {Injectable} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {BehaviorSubject} from 'rxjs';
import {PetToOwnerInterface} from '../models/pet/pet-to-owner.models';
import {CrudType} from 'src/app/common/crud-types';
import {getReferenceBreedStore, getReferencePetIdentifierType, getReferencePetTypeStore} from '../store/crud/crud.selectors';
import {CrudState} from '../api/api-connector/crud/crud-store.service';
import {LoadCreateAction, LoadDeleteAction, LoadGetAction, LoadGetListAction, LoadPatchAction} from '../api/api-connector/crud/crud.actions';
import {ApiParamsInterface, ApiResponse} from '../api/api-connector/api-connector.models';

@Injectable({
  providedIn: 'root'
})
export class PetsService {
  constructor(
    protected store: Store<CrudState>,
  ) {
  }

  getPetTypes() {
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferencePetType, params: {'order': {'name': 'ASC'}, limit: 500}
    }));
    return this.store.pipe(select(getReferencePetTypeStore));
  }

  getPetTypes10() {
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferencePetType, params: {'order': {'sort': 'ASC'}, limit: 10}
    }));
    return this.store.pipe(select(getReferencePetTypeStore));
  }

  getBred(idType?: number) {
    const paramsQuery: ApiParamsInterface = {'order': {'name': 'ASC'}, limit: 500};
    if (idType > 0) {
      paramsQuery['filter'] = {
        type: {id: idType}
      };
    }
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceBreed, params: paramsQuery
    }));
    return this.store.pipe(select(getReferenceBreedStore));
  }

  getBred10(idType?: number) {
    const paramsQuery: ApiParamsInterface = {'order': {sort: 'DESC', name: 'ASC'}, limit: 10};
    if (idType > 0) {
      paramsQuery['filter'] = {
        type: {id: idType}
      };
    }
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceBreed, params: paramsQuery
    }));
    return this.store.pipe(select(getReferenceBreedStore));
  }

  getPetIdentifierType() {
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferencePetIdentifierType, params: {'order': {'name': 'ASC'}, limit: 500}
    }));
    return this.store.pipe(select(getReferencePetIdentifierType));
  }

  addOwner(petId, ownerId, mainOwner: boolean = false, onSuccess?: (res) => {}) {
    const action = LoadCreateAction;
    const model = {
      owner: {id: ownerId},
      pet: {id: petId},
      mainOwner: mainOwner
    };
    this.store.dispatch(new action({
      type: CrudType.PetToOwner,
      params: <PetToOwnerInterface>model,
      onSuccess: (res) => {
        if (onSuccess) {
          onSuccess(res);
        }
      }
    }));
  }

  getOwners(idPet) {
    const params = {filter: {pet: {id: idPet}}};
    const then = new BehaviorSubject(<PetToOwnerInterface>{});
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Owner,
      params: <ApiParamsInterface>params,
      onSuccess(res: ApiResponse) {
        if (res) {
          then.next(<PetToOwnerInterface>res.response);
        }
      }
    }));
    return then;
  }

  setOwnerAsMain(petToOwnerId) {
    const then = new BehaviorSubject(<ApiResponse>{});
    const model = {
      id: parseInt(petToOwnerId, 10),
      mainOwner: true
    };
    this.store.dispatch(new LoadPatchAction({
      type: CrudType.PetToOwner,
      params: <any>model,
      onSuccess(res: ApiResponse) {
        then.next(res);
      }
    }));
    return then;
  }

  getById(petId) {
    const then = new BehaviorSubject(<ApiResponse>{});
    this.store.dispatch(new LoadGetAction({
      type: CrudType.Pet,
      params: petId,
      onSuccess(res: ApiResponse) {
        then.next(res);
      }
    }));
    return then;
  }

  removeOwner(petToOwnerId) {
    const then = new BehaviorSubject(<ApiResponse>{});
    this.store.dispatch(new LoadDeleteAction({
      type: CrudType.PetToOwner,
      params: petToOwnerId,
      onSuccess(res: ApiResponse) {
        then.next(res);
      }
    }));
    return then;
  }

  remove(id: number) {
    const then = new BehaviorSubject(<ApiResponse>{});
    this.store.dispatch(new LoadDeleteAction({
      type: CrudType.Pet,
      params: {id: id},
      onSuccess(res: ApiResponse) {
        then.next(res);
      }
    }));
    return then;
  }
}
