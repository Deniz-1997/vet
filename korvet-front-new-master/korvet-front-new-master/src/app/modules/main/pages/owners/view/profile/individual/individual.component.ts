import {Component, OnInit} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {ViewService} from '../../view.service';
import {map} from 'rxjs/operators';
import {PetToOwnerModel} from '../../../../../../../models/pet/pet-to-owner.models';
import {MatDialog} from '@angular/material/dialog';
import {ModalConfirmComponent} from '../../../../../../shared/components/modal-confirm/modal-confirm.component';
import {Observable} from 'rxjs';
import {EventModel} from '../../../../../../../models/event.models';
import {Router} from '@angular/router';
import {ReferenceAppointmentStatusModel} from '../../../../../../../models/reference/reference.appointment.status.models';
import {ReferenceEventStatusModel} from '../../../../../../../models/reference/reference.event.status';
import {ModalEventActionsViewComponent} from '../../../../../../shared/modules/modal-event-actions-view/modal-event-actions-view.component';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadDeleteAction, LoadGetAction, LoadPatchAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData, getCrudModelTotalCount, getCrudModelGetListLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './individual.component.html'})

export class IndividualComponent implements OnInit {

  events$: Observable<EventModel[]>;
  eventsTotalCount$: Observable<number>;
  eventsLoading$: Observable<boolean>;
  eventType = CrudType.Event;
  eventStatuses$: Observable<{ label: string, value: ReferenceEventStatusModel }[]>;
  c = '#';
  d = 'demo';

  constructor(
    private store: Store<CrudState>,
    private service: ViewService,
    private dialog: MatDialog,
    private router: Router,
  ) {
    this.events$ = store.pipe(select(getCrudModelData, {type: CrudType.Event, params: {}}));
    this.eventsTotalCount$ = store.pipe(select(getCrudModelTotalCount, {type: CrudType.Event}));
    this.eventsLoading$ = store.pipe(select(getCrudModelGetListLoading, {type: CrudType.Event}));

    this.eventStatuses$ = store.pipe(
      select(getCrudModelData, {type: CrudType.ReferenceEventStatus}),
      map(statuses => statuses.map(status => ({label: status.name, value: status})))
    );
  }

  get owner$() {
    return this.service.owner$;
  }

  get owner() {
    return this.service.owner;
  }

  ngOnInit() {
    this.owner$.subscribe(() => {
      const petsIds = {};
      this.owner.pets.forEach(petToOwner => {
        petsIds[petToOwner.pet.id] = petToOwner;

        if (petToOwner.pet.vaccinationDate) {
          petToOwner.pet.vaccinationExpired = new Date(petToOwner.pet.vaccinationDate.replace(/(\d+).(\d+).(\d+)/, '$3/$2/$1'));
          petToOwner.pet.vaccinationExpired.setFullYear(petToOwner.pet.vaccinationExpired.getFullYear() + 1);

          const today = new Date();

          if (today >= petToOwner.pet.vaccinationExpired) {
            petToOwner.pet.vaccinationInvalid = true;
          }
        }

      });

      this.owner.pets.sort((a, b) => +a.pet.isDead - +b.pet.isDead);
      if (Object.keys(petsIds).length) {
        this.store.dispatch(new LoadGetListAction({
          type: CrudType.Event,
          params: {
            filter: {pet: {id: Object.keys(petsIds).map(key => key)}},
            order: {date: 'DESC'},
          }
        }));
      }
    });
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceEventStatus,
      params: {order: {sort: 'ASC', name: 'ASC'}}
    }));
  }

  deletePetToOwners(petToOwner: PetToOwnerModel): void {
    const dialog = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: 'Вы точно хотите удалить животное у владельца?',
        headComment: 'Действие необратимо. Животное удалено не будет, его можно будет найти позже в списке животных.',
        actions: [
          {title: 'Отмена', class: 'btn-st btn-st--left btn-st--gray', action: false},
          {title: 'Удалить', class: 'btn-st btn-st--right btn-st--red', action: true},
        ]
      }
    });
    dialog.afterClosed().subscribe(answer => {
      if (answer) {
        this.store.dispatch(new LoadDeleteAction({
          type: CrudType.PetToOwner,
          params: {id: petToOwner.id},
          onSuccess: () => this.store.dispatch(new LoadGetAction({type: CrudType.Owner, params: this.service.id}))
        }));
      }
    });
  }

  eventClick(event: EventModel): void {
    const dialog = this.dialog.open(ModalEventActionsViewComponent, {data: event});
    dialog.afterClosed().subscribe(answer => {
      switch (answer) {
        case 'edit':
          this.router.navigate(['/owners', this.owner.id, 'events', event.id]).then();
          break;
        /*case 'delete':
          this.store.dispatch(new LoadDeleteAction({type: CrudType.Event, params: {id: event.id}}));
          break;*/
      }
    });
  }

  changeEventStatus(status: ReferenceAppointmentStatusModel, event: EventModel): void {
    this.store.dispatch(new LoadPatchAction({
      type: CrudType.Event,
      params: <any>{
        id: event.id,
        status: status,
      }
    }));
  }
}
