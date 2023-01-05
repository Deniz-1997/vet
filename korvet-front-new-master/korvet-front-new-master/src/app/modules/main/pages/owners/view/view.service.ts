import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {OwnerModel} from '../../../../../models/owner/owner.models';
import {select, Store} from '@ngrx/store';
import {filter, tap} from 'rxjs/operators';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {getCrudModelStoreId} from 'src/app/api/api-connector/crud/crud.selectors';

@Injectable()
export class ViewService {

  owner$: Observable<OwnerModel>;
  owner: OwnerModel;

  constructor(private store: Store<CrudState>) {
  }

  private _id: string;

  get id() {
    return this._id;
  }

  set id(value) {
    this._id = value;
    this.owner$ = this.store.pipe(
      select(getCrudModelStoreId, {
        type: CrudType.Owner,
        params: value,
      }),
      filter(owner => !!owner),
      tap(owner => this.owner = owner)
    );
  }
}
