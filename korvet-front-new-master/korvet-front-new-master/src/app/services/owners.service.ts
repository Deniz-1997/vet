import {Injectable} from '@angular/core';
import {OwnerInterface, OwnerModel} from '../models/owner/owner.models';
import {BehaviorSubject} from 'rxjs';
import {Store} from '@ngrx/store';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from '../api/api-connector/crud/crud-store.service';
import {ApiResponse} from '../api/api-connector/api-connector.models';
import {LoadCreateAction} from '../api/api-connector/crud/crud.actions';

@Injectable({
  providedIn: 'root'
})
export class OwnersService {

  constructor(
    protected store: Store<CrudState>,
  ) {
  }

  add(owner: OwnerModel) {
    const then = new BehaviorSubject(<ApiResponse>{});
    this.store.dispatch(new LoadCreateAction({
      type: CrudType.Owner,
      params: <OwnerInterface>owner,
      onSuccess(res: ApiResponse) {
        then.next(res);
      }
    }));
    return then;
  }
}
