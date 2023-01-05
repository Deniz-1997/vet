import {Component, Input, OnDestroy, OnInit} from '@angular/core';
import {Observable, Subscription} from 'rxjs';
import {EventModel} from '../../../../../../models/event.models';
import {UsersService} from '../../../../../../services/users.service';
import {ReferenceEventStatusInterface, ReferenceEventStatusModel} from '../../../../../../models/reference/reference.event.status';
import {select, Store} from '@ngrx/store';
import {ViewService} from '../view.service';
import {PetModel} from '../../../../../../models/pet/pet.models';
import {filter, map} from 'rxjs/operators';
import {ModalEventActionsViewComponent} from '../../../../../shared/modules/modal-event-actions-view/modal-event-actions-view.component';
import {MatDialog} from '@angular/material/dialog';
import {Router} from '@angular/router';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadPatchAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData, getCrudModelGetListLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-pets-view-events',
  templateUrl: './events.component.html',
})
export class EventsComponent implements OnInit, OnDestroy {
  eventType = CrudType.Event;
  events$: Observable<EventModel[]>;
  @Input() pet$ = new PetModel();
  @Input() limit = 0;
  loading$: Observable<boolean>;
  @Input() eventStatuses$: Observable<{ label: string, value: ReferenceEventStatusModel }[]>;
  private subscriptions: Subscription[] = [];
  c = '#';
  d = 'demo';

  constructor(
    private userApiService: UsersService,
    public petsViewService: ViewService,
    private store: Store<CrudState>,
    private dialog: MatDialog,
    private router: Router,
  ) {
    const s = petsViewService.pet.subscribe(pet => {
      if (pet && pet.id) {
        this.store.dispatch(new LoadGetListAction({
          type: CrudType.Event,
          params: {'filter': {'pet': {'id': pet.id}}, order: {date: 'DESC'}}
        }));
      }
    });
    this.subscriptions.push(s);

    this.events$ = store.pipe(select(getCrudModelData, {type: CrudType.Event}));
    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: CrudType.Event}));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceEventStatus,
      params: {order: {sort: 'ASC', name: 'ASC'}}
    }));
    this.eventStatuses$ = store.pipe(
      select(getCrudModelData, {type: CrudType.ReferenceEventStatus}),
      filter(statuses => !!statuses),
      map(statuses => statuses.map(status => ({label: status.name, value: status, color: status.color})))
    );
  }

  ngOnInit() {
  }

  ngOnDestroy() {
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

  getStatus(status?: ReferenceEventStatusInterface) {
    return status ? status.name : '-';
  }

  changeEventStatus(status: ReferenceEventStatusModel, event: EventModel): void {
    this.store.dispatch(new LoadPatchAction({
      type: CrudType.Event,
      params: <any>{
        id: event.id,
        status: status,
      }
    }));
  }

  eventClick(event: EventModel): void {
    const dialog = this.dialog.open(ModalEventActionsViewComponent, {data: event});
    dialog.afterClosed().subscribe(answer => {
      switch (answer) {
        case 'edit':
          this.router.navigate(['/pets', event.pet.id, 'events', event.id]).then();
          break;
      }
    });
  }

}
