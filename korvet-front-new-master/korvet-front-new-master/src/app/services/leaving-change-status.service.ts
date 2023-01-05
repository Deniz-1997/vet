import {Injectable} from '@angular/core';
import {Store} from '@ngrx/store';
import {CrudType} from '../common/crud-types';
import {ReferenceLeavingStatusModel} from '../models/reference/reference.leaving.status.models';
import {LeavingModel} from '../models/leaving/leaving.models';
import {CrudState} from '../api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadPatchAction} from '../api/api-connector/crud/crud.actions';


@Injectable({
  providedIn: 'root'
})
export class LeavingChangeStatusService {

  constructor(
    protected store: Store<CrudState>,
  ) {
  }
  public changeLeavingStatus(leavingStatus: ReferenceLeavingStatusModel, leaving: LeavingModel): void {
    this.store.dispatch(new LoadPatchAction({
      type: CrudType.Leaving,
      params: <any>{
        id: leaving.id,
        leavingStatus: leavingStatus,
      }
    }));
  }
  public changeLeavingStatusCode(code: string, leaving: LeavingModel): void {
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceLeavingStatus,
      params: {filter : {code: code}},
      onSuccess: (res) => {
        if (res && res.status === true) {
          const leavingStatus = res.response.items[0];
          this.changeLeavingStatus(leavingStatus, leaving);
        }
      }
    }));
  }

}
