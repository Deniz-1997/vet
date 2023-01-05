import {Component, OnInit} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {ViewService} from '../view.service';
import {Observable} from 'rxjs';
import {PetModel} from '../../../../../../models/pet/pet.models';
import {filter, map} from 'rxjs/operators';
import {ReferenceEventTypeModel} from '../../../../../../models/reference/reference.event.type.models';
import {ActivatedRoute, Router} from '@angular/router';
import {EventFormInterface} from '../../../../../../interfaces/event-form.interface';
import {EventModel} from '../../../../../../models/event.models';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadGetAction, LoadPatchAction, LoadCreateAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData, getCrudModelStoreId, getCrudModelCreatePatchLoading, getCrudModelGetLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './events.component.html'})

export class EventsComponent implements OnInit {

  users$: Observable<{ id: number, fullName: string }[]>;
  pets$: Observable<PetModel[]>;
  eventTypes$: Observable<ReferenceEventTypeModel[]>;
  loading$: Observable<boolean>;
  loadingData$: Observable<boolean>;
  event$: Observable<EventModel>;
  ownerId: number;

  constructor(
    private store: Store<CrudState>,
    private service: ViewService,
    private router: Router,
    private route: ActivatedRoute,
  ) {
    this.users$ = store.pipe(select(getCrudModelData, {type: CrudType.User})).pipe(
      map(item => {
        return item.map(user => {
            return {id: user['id'], fullName: user.getFullName()};
          }
        );
      })
    );

    this.pets$ = store.pipe(
      select(getCrudModelStoreId, {type: CrudType.Owner, params: service.id}),
      filter(owner => !!owner),
      map(owner => owner.pets.map(petToOwner => petToOwner.pet))
    );

    this.pets$ = this.store.pipe(select(getCrudModelData, {type: CrudType.Pet}));

    if (service.id) {
      this.ownerId = +service.id;
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.Pet,
        params: {
          filter: {owners: {owner: {id: +service.id}}},
          order: {name: 'ASC'},
          offset: 0, limit: 10,
          fields: {0: 'id', 2: 'name'}
        }
      }));
    }

    this.eventTypes$ = store.pipe(select(getCrudModelData, {type: CrudType.ReferenceEventType}));
    this.loading$ = store.pipe(select(getCrudModelCreatePatchLoading, {type: CrudType.Event}));
  }

  ngOnInit() {
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.User,
      params: {
        fields: {0: 'id', 1: 'surname', 2: 'name', 3: 'patronymic'},
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));
    this.store.dispatch(new LoadGetListAction({type: CrudType.ReferenceEventType}));
    const eventId = this.route.snapshot.paramMap.get('eventId');
    if (eventId) {
      this.store.dispatch(new LoadGetAction({type: CrudType.Event, params: eventId}));
      this.event$ = this.store.pipe(select(getCrudModelStoreId, {type: CrudType.Event, params: eventId}));
      this.loadingData$ = this.store.pipe(select(getCrudModelGetLoading, {type: CrudType.Event}));
    }
  }

  cancel(): void {
    this.router.navigate(['../'], {relativeTo: this.route.parent}).then();
  }

  submit(value: EventFormInterface): void {
    const action = this.event$ ? LoadPatchAction : LoadCreateAction;
    this.store.dispatch(new action({
      type: CrudType.Event,
      params: <any>value,
      onSuccess: () => this.cancel(),
    }));
  }
}
