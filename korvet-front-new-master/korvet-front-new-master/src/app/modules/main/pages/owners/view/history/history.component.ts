import { Component, OnDestroy, OnInit } from '@angular/core';
import { combineLatest, Observable, Subject } from 'rxjs';
import { select, Store } from '@ngrx/store';
import { ViewService } from '../view.service';
import { map, takeUntil } from 'rxjs/operators';
import { AppointmentModel } from '../../../../../../models/appointment/appointment.models';
import { ReferenceAppointmentStatusModel } from '../../../../../../models/reference/reference.appointment.status.models';
import { CrudType } from 'src/app/common/crud-types';
import {OwnerModel} from '../../../../../../models/owner/owner.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadPatchAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData, getCrudModelGetListLoading, getCrudModelPatchLoading, getCrudModelAppendListLoading, getCrudModelTotalCount} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({ templateUrl: './history.component.html' })

export class HistoryComponent implements OnInit, OnDestroy {
  appointments$: Observable<AppointmentModel[]>;
  loading$: Observable<boolean>;
  appendLoading$: Observable<boolean>;
  totalCount$: Observable<number>;
  appointments: AppointmentModel[] = [];
  owners = new OwnerModel();
  appointmentStatuses$: Observable<{ label: string, value: ReferenceAppointmentStatusModel }[]>;
  type = CrudType.Owner;
  limit = 40;
  offset = 0;
  navigateUrl = ['add'];
  private destroy$ = new Subject<any>();
  c = '#';
  d = 'demo';

  constructor(
    private store: Store<CrudState>,
    private service: ViewService
  ) {
    this.appointments$ = store.pipe(select(getCrudModelData, { type: CrudType.Appointment }));
    this.loading$ = combineLatest(
      store.pipe(select(getCrudModelGetListLoading, { type: CrudType.Appointment })),
      store.pipe(select(getCrudModelPatchLoading, { type: CrudType.Appointment })),
    ).pipe(map(([getListLoading, patchLoading]) => getListLoading || patchLoading));
    this.appendLoading$ = store.pipe(select(getCrudModelAppendListLoading, { type: this.type }));
    this.appointmentStatuses$ = store.pipe(
      select(getCrudModelData, { type: CrudType.ReferenceAppointmentStatus }),
      map(statuses => statuses.map(status => ({ label: status.name, value: status })))
    );
    this.totalCount$ = store.pipe(select(getCrudModelTotalCount, { type: CrudType.Appointment }));
    service.owner$.subscribe(owner => this.owners = owner );

    this.appointments$
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(
        item => {
          if (item && item.length > 0) {
            const prim = item.filter(i => i.type && i.type.code === 'PRIMARY');
            const sec = item.filter(i => i.type && i.type.code !== 'PRIMARY');
            const evsd = item.filter(i => i.type && i.type.code === 'EVSD');
            this.appointments = [];
            evsd.map(a => {
              this.appointments.push(a);
            });
            prim.map(primary => {
              this.appointments.push(primary);
              sec.map(secondary => {
                if (secondary.previous && primary.id === secondary.previous.id) {
                  this.appointments.push(secondary);
                }
              });
            });
            this.appointments.sort((a, b) => {
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
      this.store.dispatch(new LoadGetListAction({ type: CrudType.Appointment, params: { filter: { owner: { id: owner.id } } } }));
    });
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceAppointmentStatus,
      params: { order: { sort: 'ASC', name: 'ASC' } }
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

  ngOnDestroy(): void {
    this.destroy$.next();
  }
  selectedIndexChange(event) {
    if (event === 1) {
      this.navigateUrl = ['add-leaving'];
    } else if (event === 0) {
      this.navigateUrl = ['add'];
    }
  }
}
