import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {UserModels} from '../../../../../../models/user/user.models';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelGetLoading, getCrudModelStoreId} from 'src/app/api/api-connector/crud/crud.selectors';

@Injectable({providedIn: 'root'})

export class ViewService {
  user: Observable<UserModels>;
  loading$: Observable<boolean>;
  type = CrudType.User;

  constructor(
    private store: Store<CrudState>,
  ) {
    this.loading$ = this.store.pipe(select(getCrudModelGetLoading, {type: this.type}));
  }

  private _id: string;

  get id(): string {
    return this._id;
  }

  set id(value: string) {
    this._id = value;
    if (this._id) {
      this.getUser().user = this.store.pipe(select(getCrudModelStoreId, {type: this.type, params: this.id}));
    }
  }

  getUser(): this {
    this.store.dispatch(new LoadGetAction({type: this.type, params: this._id}));
    return this;
  }
}
