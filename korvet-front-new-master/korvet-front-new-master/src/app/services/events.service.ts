import {Injectable} from '@angular/core';
import {BehaviorSubject} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {CrudType} from 'src/app/common/crud-types';
import {getReferenceEventTypeStore} from '../store/crud/crud.selectors';
import {CrudState} from '../api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from '../api/api-connector/crud/crud.actions';
import {ApiResponse} from '../api/api-connector/api-connector.models';

@Injectable({
  providedIn: 'root'
})
export class EventsService {

  constructor(
    protected store: Store<CrudState>,
  ) {
  }

  getType() {
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceEventType, params: {'order': {'name': 'ASC'}, limit: 500}
    }));
    return this.store.pipe(select(getReferenceEventTypeStore));
  }

  getStatuses() {
    const then = new BehaviorSubject(<ApiResponse>{});
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceEventStatus,
      params: {order: {sort: 'ASC'}},
      onSuccess(res: ApiResponse) {
        then.next(res);
      }
    }));
    return then;
  }
}
