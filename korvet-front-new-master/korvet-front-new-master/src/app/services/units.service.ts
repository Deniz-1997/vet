import {Injectable} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from '../api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from '../api/api-connector/crud/crud.actions';
import {getReferenceUnitItem} from '../store/crud/crud.selectors';

@Injectable({
  providedIn: 'root'
})
export class UnitsService {
  constructor(protected store: Store<CrudState>) {
  }

  getUnits() {
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceUnit, params: {'order': {'name': 'ASC'}, limit: 500}
    }));
    return this.store.pipe(select(getReferenceUnitItem));
  }
}
