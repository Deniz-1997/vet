import {Injectable} from '@angular/core';
import {BehaviorSubject} from 'rxjs';
import {Store} from '@ngrx/store';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from '../api/api-connector/crud/crud-store.service';
import {LoadDeleteAction} from '../api/api-connector/crud/crud.actions';
import {ApiResponse} from '../api/api-connector/api-connector.models';

@Injectable({
  providedIn: 'root'
})
export class MonitoredObjectService {

  type = CrudType.MonitoredObject;

  constructor(protected store: Store<CrudState>) {
  }

  remove(id: number) {
    const then = new BehaviorSubject(<ApiResponse>{});
    this.store.dispatch(new LoadDeleteAction({
      type: this.type,
      params: {id: id},
      onSuccess(res: ApiResponse) {
        then.next(res);
      }
    }));
    return then;
  }
}
