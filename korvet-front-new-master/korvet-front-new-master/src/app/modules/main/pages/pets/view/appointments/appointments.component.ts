import {Component, Input, OnDestroy, OnInit} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {ViewService} from '../view.service';
import {Observable, Subject, Subscription} from 'rxjs';
import {AppointmentModel} from '../../../../../../models/appointment/appointment.models';
import {OwnerModel} from '../../../../../../models/owner/owner.models';
import {UserModels} from '../../../../../../models/user/user.models';
import {ReferenceAppointmentStatusModel} from '../../../../../../models/reference/reference.appointment.status.models';
import {filter, map, takeUntil} from 'rxjs/operators';
import {PetModel} from '../../../../../../models/pet/pet.models';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadPatchAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelGetListLoading, getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-pets-view-appointments',
  templateUrl: './appointments.component.html',
  styleUrls: ['./appointments.component.css']
})
export class AppointmentsComponent implements OnInit, OnDestroy {
  appointments: AppointmentModel[] = [];
  @Input() pet$ = new PetModel();
  @Input() limit = 0;
  @Input() appointmentStatuses$: Observable<{ label: string, value: ReferenceAppointmentStatusModel }[]>;
  loading$: Observable<boolean>;
  private destroy$ = new Subject<any>();
  private subscriptions: Subscription[] = [];
  pet = new PetModel();
  navigateUrl = 'add-appointment';
  c = '#';
  d = 'demo';
  fields = {
    0: 'id',
    1: 'date',
    2: 'name',
    3: 'state',
    4: 'dateEnd',
    5: 'actions',
    6: 'listActions',
    7: 'data',
    'owner': ['id', 'name', 'contractDateTo'],
    'profession': ['id', 'name'],
    'user': ['id', 'surname', 'name', 'patronymic'],
    'type': ['title'],
    'previous': ['id', 'date', 'name'],
    'pet': {
      0: 'id', 1: 'type', 2: 'name', 3: 'breed', 4: 'gender', 5: 'petToOwnerId', 6: 'isDead',
      7: 'birthday', 8: 'dateOfDeath', 9: 'chipNumber', 10: 'isSterilized', 11: 'aggressive'
    }
  };

  constructor(
    public petsViewService: ViewService,
    private store: Store<CrudState>,
  ) {
    const s = petsViewService.pet.subscribe(pet => {
      if (pet && pet.id) {
        this.pet = pet;
        this.store.dispatch(new LoadGetListAction({
          type: CrudType.Appointment,
          params: {
            filter: {pet: {id: pet.id}},
            fields: this.fields,
            order: {date: 'DESC'},
            limit: 100
          }
        }));
      }
    });
    this.subscriptions.push(s);
    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: CrudType.Appointment}));

    store.pipe(select(getCrudModelData, {type: CrudType.Appointment}))
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(
        item => {
          const prim = item.filter(i => i.type && i.type.code === 'PRIMARY');
          const sec = item.filter(i => i.type && i.type.code !== 'PRIMARY');
          const evsd = item.filter(i => i.type && i.type.code === 'EVSD');

          this.appointments = [];
          evsd.map(a => {
            this.appointments.push(a);
          });
          prim.map(i => {
            this.appointments.push(i);
            sec.map(app => {
              if (i.id === app.previous.id) {
                this.appointments.push(app);
              }
            });
          });

          this.appointments.sort((a, b) => {
            return a.id > b.id ? -1 : 1;
          });
        }
      );

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceAppointmentStatus,
      params: {order: {sort: 'ASC', name: 'ASC'}}
    }));
    this.appointmentStatuses$ = store.pipe(
      select(getCrudModelData, {type: CrudType.ReferenceAppointmentStatus}),
      filter(statuses => !!statuses),
      map(statuses => statuses.map(status => ({label: status.name, value: status, color: status.color})))
    );
  }

  ngOnInit() {
  }

  ngOnDestroy() {
    this.destroy$.next();

    this.subscriptions
      .forEach(s => s.unsubscribe());
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
  selectedIndexChange(event) {
    if (event === 1) {
      this.navigateUrl = 'add-leaving';
    } else if (event === 0) {
      this.navigateUrl = 'add-appointment';
    }
  }

}
