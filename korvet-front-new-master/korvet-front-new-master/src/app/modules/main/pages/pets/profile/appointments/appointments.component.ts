import {Component, Input, OnInit} from '@angular/core';
import {Observable} from 'rxjs';
import {AppointmentModel} from '../../../../../../models/appointment/appointment.models';
import {OwnerModel} from '../../../../../../models/owner/owner.models';
import {UserModels} from '../../../../../../models/user/user.models';
import {ReferenceAppointmentStatusModel} from '../../../../../../models/reference/reference.appointment.status.models';
import {PetModel} from '../../../../../../models/pet/pet.models';
import {select, Store} from '@ngrx/store';
import {filter, map} from 'rxjs/operators';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadPatchAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({selector: 'app-pets-profile-appointments', templateUrl: './appointments.component.html'})

export class AppointmentsComponent implements OnInit {
  @Input() items$: Observable<AppointmentModel[]>;
  @Input() pet$: Observable<PetModel[]>;
  @Input() limit = 0;
  @Input() appointmentStatuses$: Observable<{ label: string, value: ReferenceAppointmentStatusModel }[]>;

  constructor(private store: Store<CrudState>) {
    this.appointmentStatuses$ = store.pipe(
      select(getCrudModelData, {type: CrudType.ReferenceAppointmentStatus}),
      filter(statuses => !!statuses),
      map(statuses => statuses.map(status => ({label: status.name, value: status})))
    );
  }

  ngOnInit() {
  }

  getDate(date: string) {
    return date.substr(0, 10);
  }

  getTime(date: string) {
    return date.substr(11, 5);
  }

  showAll($event) {
    if ($event) {
      $event.preventDefault();
    }
    this.limit = 0;
  }

  getOwner(owner?: OwnerModel) {
    return owner ? owner.name : '-';
  }

  getUser(user?: UserModels) {
    return user ? user.name : '-';
  }

  getStatus(status?: ReferenceAppointmentStatusModel) {
    return status ? status.name : '-';
  }

  changeStatus(status: ReferenceAppointmentStatusModel, appointment: AppointmentModel): void {
    this.store.dispatch(new LoadPatchAction({
      type: CrudType.Appointment,
      params: <any>{
        id: appointment.id,
        status: status,
      }
    }));
  }
}
