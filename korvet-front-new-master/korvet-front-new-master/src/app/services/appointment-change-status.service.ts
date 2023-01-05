import {Injectable} from '@angular/core';
import {Store} from '@ngrx/store';
import {CrudType} from '../common/crud-types';
import {ReferenceAppointmentStatusModel} from '../models/reference/reference.appointment.status.models';
import {AppointmentModel} from '../models/appointment/appointment.models';
import {CrudState} from '../api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadPatchAction} from '../api/api-connector/crud/crud.actions';


@Injectable({
  providedIn: 'root'
})
export class ChangeAppointmentStatusService {

  constructor(
    protected store: Store<CrudState>,
  ) {
  }
  public changeAppointmentStatus(status: ReferenceAppointmentStatusModel, appointment: AppointmentModel): void {
    this.changeAppointmentStatusById(status, appointment.id);
  }
  public changeAppointmentStatusCode(code: string, appointmentId: number): void {
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceAppointmentStatus,
      params: {filter : {code: code}},
      onSuccess: (res) => {
        if (res && res.status === true) {
          const status = res.response.items[0];
          this.changeAppointmentStatusById(status, appointmentId);
        }
      }
    }));
  }
  private changeAppointmentStatusById(status: ReferenceAppointmentStatusModel, appointmentId: number): void {
    this.store.dispatch(new LoadPatchAction({
      type: CrudType.Appointment,
      params: <any>{
        id: appointmentId,
        status: status,
      }
    }));
  }
}
