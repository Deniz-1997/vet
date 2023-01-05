import {Component, OnDestroy, OnInit} from '@angular/core';
import {combineLatest, Observable, Subject} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {ViewService} from '../../view.service';
import {map, takeUntil} from 'rxjs/operators';
import {AppointmentModel} from '../../../../../../../models/appointment/appointment.models';
import {ReferenceAppointmentStatusModel} from '../../../../../../../models/reference/reference.appointment.status.models';
import {CrudType} from 'src/app/common/crud-types';
import {LeavingModel} from '../../../../../../../models/leaving/leaving.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadPatchAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData, getCrudModelGetListLoading, getCrudModelPatchLoading, getCrudModelAppendListLoading, getCrudModelTotalCount} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './detail.component.html'})

export class DetailComponent implements OnInit, OnDestroy {
  appointments$: Observable<AppointmentModel[]>;
  leaving$: Observable<LeavingModel[]>;
  appointment = [];
  loading$: Observable<boolean>;
  appendLoading$: Observable<boolean>;
  totalCount$: Observable<number>;
  appointments: AppointmentModel[] = [];
  leaving: LeavingModel[] = [];
  appointmentStatuses$: Observable<{ label: string, value: ReferenceAppointmentStatusModel }[]>;
  type = CrudType.Owner;
  limit = 40;
  offset = 0;
  private destroy$ = new Subject<any>();

  constructor(
    private store: Store<CrudState>,
    private service: ViewService,
  ) {
    this.appointments$ = store.pipe(select(getCrudModelData, {type: CrudType.Appointment}));
    this.leaving$ = store.pipe(select(getCrudModelData, {type: CrudType.Leaving}));
    this.loading$ = combineLatest(
      store.pipe(select(getCrudModelGetListLoading, {type: CrudType.Appointment})),
      store.pipe(select(getCrudModelPatchLoading, {type: CrudType.Appointment})),
    ).pipe(map(([getListLoading, patchLoading]) => getListLoading || patchLoading));
    this.appendLoading$ = store.pipe(select(getCrudModelAppendListLoading, {type: this.type}));
    this.appointmentStatuses$ = store.pipe(
      select(getCrudModelData, {type: CrudType.ReferenceAppointmentStatus}),
      map(statuses => statuses.map(status => ({label: status.name, value: status})))
    );
    this.totalCount$ = store.pipe(select(getCrudModelTotalCount, {type: CrudType.Appointment}));

    this.appointments$
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(
        item => {
          if (item && item.length > 0) {
            const prim = item.filter(i => i.type.code === 'PRIMARY');
            const sec = item.filter(i => i.type.code !== 'PRIMARY');

            this.appointments = [];
            prim.concat(sec).map(i => {
              this.appointments.push(i);
            });
            this.appointments.sort((a, b) => {
              return a.id > b.id ? -1 : 1;
            });
          }
        }
      );
    this.leaving$
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(
        item => {
          if (item && item.length > 0) {
            const prim = item.filter(i => i.type.code === 'PRIMARY');
            const sec = item.filter(i => i.type.code !== 'PRIMARY');

            this.leaving = [];
            prim.concat(sec).map(e => {
              this.leaving.push(e);
            });
            this.leaving.sort((a, b) => {
              return a.id > b.id ? -1 : 1;
            });
          }
        }
      );
  }

  get owner$() {
    return this.service.owner$;
  }

  get owner() {
    return this.service.owner;
  }

  ngOnInit() {
    this.owner$.subscribe(owner => {
      this.store.dispatch(new LoadGetListAction({
          type: CrudType.Appointment,
          params: {
            filter: {owner: {id: owner.id}},
            fields: [
              'id',
              'type',
              'date',
              'pet',
              'owner',
              'profession',
              'user',
              'name',
              'survey',
              'diagnosis',
              'prescription',
              'appointmentFormTemplate',
              'appointmentWeight',
              'appointmentTemperature',
              'weightNotMeasured',
              'temperatureNotMeasured'
            ],
            order: {date: 'DESC'}
          }
        })
      );
      this.store.dispatch(new LoadGetListAction({
          type: CrudType.Leaving,
          params: {
            filter: {owner: {id: owner.id}},
            fields: [
              'id',
              'type',
              'date',
              'pet',
              'owner',
              'profession',
              'user',
              'name',
              'survey',
              'diagnosis',
              'prescription',
              'appointmentFormTemplate',
              'leavingWeight',
              'leavingTemperature',
              'weightNotMeasured',
              'temperatureNotMeasured'
            ],
            order: {date: 'DESC'}
          }
        })
      );
    });

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceAppointmentStatus,
      params: {order: {sort: 'ASC', name: 'ASC'}}
    }));
  }

  changeAppointmentStatus(status: ReferenceAppointmentStatusModel, appointment: AppointmentModel): void {
    this.store.dispatch(new LoadPatchAction({
      type: CrudType.Appointment,
      params: <any>{
        id: appointment.id,
        status: status,
      }
    }));
  }

  isSecond(appointment) {
    return appointment.type && appointment.type.code === 'SECONDARY';
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }
}
