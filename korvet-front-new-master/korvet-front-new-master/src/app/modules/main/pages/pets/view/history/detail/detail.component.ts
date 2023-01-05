import {Component, OnDestroy, OnInit} from '@angular/core';
import {Observable, Subject} from 'rxjs';
import {AppointmentModel} from '../../../../../../../models/appointment/appointment.models';
import {select, Store} from '@ngrx/store';
import {ViewService} from '../../view.service';
import {takeUntil} from 'rxjs/operators';
import {CrudType} from 'src/app/common/crud-types';
import {LeavingModel} from '../../../../../../../models/leaving/leaving.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData, getCrudModelGetListLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  templateUrl: './detail.component.html',
  styleUrls: ['./detail.component.css']
})
export class DetailComponent implements OnInit, OnDestroy {
  appointments: AppointmentModel[] = [];
  loading$: Observable<boolean>;
  private destroy$ = new Subject<any>();
  leaving: LeavingModel[] = [];
  leaving$: Observable<LeavingModel[]>;

  constructor(
    public petsViewService: ViewService,
    private store: Store<CrudState>,
  ) {
    this.leaving$ = store.pipe(select(getCrudModelData, {type: CrudType.Leaving}));
    const s = petsViewService.pet.subscribe(pet => {
      if (pet && pet.id) {
        this.store.dispatch(new LoadGetListAction({
          type: CrudType.Appointment,
          params: {
            filter: {'pet': {'id': pet.id}},
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
        }));
        this.store.dispatch(new LoadGetListAction({
            type: CrudType.Leaving,
            params: {
              filter: {pet: {id: pet.id}},
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
      }
    });
  }

  ngOnInit() {
    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: CrudType.Appointment}));
    this.store.pipe(select(getCrudModelData, {type: CrudType.Appointment}))
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(
        item => {
          const prim = item.filter(i => i.type && i.type.code === 'PRIMARY');
          const sec = item.filter(i => i.type && i.type.code !== 'PRIMARY');

          this.appointments = [];
          prim.concat(sec).map(i => {
            this.appointments.push(i);
          });
          this.appointments.sort((a, b) => {
            return a.id > b.id ? -1 : 1;
          });
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

  isSecond(appointment) {
    return appointment.type && appointment.type.code === 'SECONDARY';
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }
}
