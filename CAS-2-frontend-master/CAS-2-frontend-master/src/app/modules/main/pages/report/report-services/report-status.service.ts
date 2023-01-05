import { Injectable } from '@angular/core';
import {EnumModel} from '../../../../../models/enum.models';
import {Observable} from 'rxjs';
import {Store} from '@ngrx/store';
import {CrudType} from '../../../../../common/crud-types';
import {CrudState} from '../../../../../api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from '../../../../../api/api-connector/crud/crud.actions';

@Injectable()
export class ReportStatusService {
  protected _status: Array<EnumModel>;

  constructor(protected store: Store<CrudState>) { }

  getStatus(): Observable<any> {
    const result = new Observable(subscriber => {
      if (this._status === undefined) {
        this.store.dispatch(new LoadGetListAction({
          type: CrudType.Enum,
          params: {
            filter: {id: 'ReportStatusEnum'}
          },
          onSuccess: ({response}) => {
            this._status = response[0]['items'];
            subscriber.next(this._status);
          }
        }));
      } else {
        subscriber.next(this._status);
      }

    });
    return result;
  }
}
