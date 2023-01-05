import {Component, OnInit} from '@angular/core';
import {Observable} from 'rxjs';
import {AppointmentModel} from '../../../../../models/appointment/appointment.models';
import {select, Store} from '@ngrx/store';
import {map} from 'rxjs/operators';
import {ReferenceAppointmentStatusModel} from '../../../../../models/reference/reference.appointment.status.models';
import {SearchModels} from '../../../../../models/search.models';
import {CrudType} from 'src/app/common/crud-types';
import {ChangeAppointmentStatusService} from '../../../../../services/appointment-change-status.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData, getCrudModelGetListLoading} from 'src/app/api/api-connector/crud/crud.selectors';


@Component({
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.css']
})
export class ListComponent extends SearchModels implements OnInit {
  crudType = CrudType;
  model = CrudType.Appointment;
  type = CrudType.Appointment;
  appointments$: Observable<AppointmentModel[]>;
  appointmentsLoading$: Observable<boolean>;
  appointmentStatuses$: Observable<{ label: string, value: ReferenceAppointmentStatusModel }[]>;
  private maxNameLength = 70;
  c = '#';
  g = '22';
  d = 'demo';
  isMd = false;
  breakPoint = 767;

  constructor(
    protected store: Store<CrudState>,
    private appointmentStatusChange: ChangeAppointmentStatusService,
  ) {
    super();

    this.appointments$ = store.pipe(select(getCrudModelData, {type: this.type}));
    this.appointmentsLoading$ = store.pipe(select(getCrudModelGetListLoading, {type: this.type}));

    this.store.dispatch(new LoadGetListAction({type: CrudType.ReferenceAppointmentStatus}));
    this.appointmentStatuses$ = store.pipe(
      select(getCrudModelData, {type: CrudType.ReferenceAppointmentStatus}),
      map(statuses => statuses.map(status => ({label: status.name, value: status})))
    );
  }

  ngOnInit() {
    super.ngOnInit();
    this.isMd = window.innerWidth <= this.breakPoint;
  }

  onResize(event) {
    this.isMd = event;
  }

  getShortName(name: string) {
    if (name && name.length > this.maxNameLength) {
      const subName = name.split(' ');
      let result = '';
      for (let i = 0; i < subName.length; i++) {
        result += subName[i] + ' ';
        if (result.length > this.maxNameLength) {
          return result + '...';
        }
      }
    }
    return name;
  }

  islongLenght(name): boolean {
    return name.length > this.maxNameLength;
  }
}
