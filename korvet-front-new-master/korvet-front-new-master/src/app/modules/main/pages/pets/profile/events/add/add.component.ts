import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { PetsService } from '../../../../../../../services/pets.service';
import { NotifyService } from '../../../../../../../services/notify.service';
import { select, Store } from '@ngrx/store';
import { BreadcrumbsService } from '../../../../../../../services/breadcrumbs.service';
import { PetModel } from '../../../../../../../models/pet/pet.models';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Observable } from 'rxjs';
import { EventsService } from '../../../../../../../services/events.service';
import { ReferenceEventTypeModel } from '../../../../../../../models/reference/reference.event.type.models';
import { filter, map } from 'rxjs/operators';
import { EventModel } from '../../../../../../../models/event.models';
import { CrudType } from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadGetAction, LoadPatchAction, LoadCreateAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelGetLoading, getCrudModelData, getCrudModelStoreId} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({ templateUrl: './add.component.html' })

export class AddComponent implements OnInit {
  crudType = CrudType;
  type = CrudType.Event;
  id: string;
  idPets: string;
  pet = new PetModel();
  public formGroup: FormGroup;
  loading$: Observable<boolean>;
  typeItems: Observable<ReferenceEventTypeModel[]>;
  showError = false;

  users$: Observable<{ id: number, fullName: string }[]>;

  public model: EventModel = new EventModel();
  public model$: Observable<EventModel>;

  constructor(private router: Router,
    protected route: ActivatedRoute,
    private petsService: PetsService,
    private notify: NotifyService,
    private eventsService: EventsService,
    private store: Store<CrudState>,
    private brdSrv: BreadcrumbsService,
  ) {
    this.typeItems = this.eventsService.getType();
    this.loading$ = this.store.pipe(select(getCrudModelGetLoading, { type: CrudType.Pet }));

    this.users$ = this.store.pipe(select(getCrudModelData, { type: CrudType.User })).pipe(
      map(item => {
        return item.map(user => {
          return { id: user['id'], fullName: user.getFullName() };
        }
        );
      })
    );
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.User,
      params: <any>{
        fields: { 0: 'id', 1: 'surname', 2: 'name', 3: 'patronymic' },
        order: { surname: 'ASC' },
        offset: 0,
        limit: 10
      }
    }));

    this.route.params.subscribe(params => {
      this.idPets = params['id'];
      if (params['idEvent'] !== 'create') {
        this.id = params['idEvent'];
        this.store.dispatch(new LoadGetAction({ type: this.type, params: this.id }));
        this.model$ = store.pipe(select(getCrudModelStoreId, { type: this.type, params: this.id }), filter(data => !!data));
        this.model$.subscribe((model) => {
          this.model = model;
          this.setModel();
          this.loading$ = this.store.pipe(select(getCrudModelGetLoading, { type: this.type }));
        });
      }
    });
  }

  static getDate(date: string) {
    return date.substr(0, 10);
  }

  static getTime(date: string) {
    return date.substr(11, 5);
  }

  ngOnInit() {
    this.petsService.getById(this.idPets).subscribe(res => {
      if (res && res.status === true) {
        this.pet = res.response;
        /*заменяем хлебные крошки*/
        this.brdSrv.replaceLabelByIndex(this.pet.name, 2);
        if (typeof this.id !== 'undefined') {
          this.brdSrv.replaceLabelByIndex('Редактировать мероприятие', 3);
        }
        /*заменяем хлебные крошки*/
      }
    });
    this.setModel();
  }

  submit($event): void {
    if ($event) {
      $event.preventDefault();
    }
    this.showError = true;
    if (this.formGroup.valid) {

      const action = this.id ? LoadPatchAction : LoadCreateAction;
      const model = { ...this.formGroup.value };
      if (this.id) {
        model.id = parseInt(this.id, 10);
      }
      if (model.user && model.user.fullName) {
        delete model.user.fullNameF;
      }
      if (new Date() < new Date(this.convertDate(model.date, model.time))) {
        model.status.id = 4;
      }

      model.date = model.date + ' ' + model.time + ':00';
      this.store.dispatch(new action({
        type: this.type,
        params: model,
        onSuccess: (res) => {
          if (res.status === true && res.response && res.response.id) {
            this.showError = false;
            const n = ['pets'];
            n.push(this.idPets);
            this.router.navigate(n).then();
          }
        }
      }));
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
    }
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      comment: new FormControl(this.model.comment ? this.model.comment : '', []),
      type: new FormGroup({
        id: new FormControl(this.model.type && this.model.type.id ? this.model.type.id : null, [Validators.required]),
      }),
      pet: new FormGroup({
        id: new FormControl(+this.idPets, [Validators.required]),
      }),
      user: new FormControl(this.model.user ? {
        id: this.model.user.id,
        fullName: this.model.user.getFullName()
      } : null,
        [Validators.required]),
      date: new FormControl(this.model.date ? AddComponent.getDate(this.model.date) : '', [Validators.required]),
      time: new FormControl(this.model.date ? AddComponent.getTime(this.model.date) : '', [Validators.required]),
      status: new FormGroup({
        id: new FormControl(this.model.status && this.model.status.id ? this.model.status.id : 1, [Validators.required]),
      }),
    });
  }

  convertDate(date: string, time: string): string {
    let datePart = date.split('.');
    return datePart[2] + '-' + datePart[1] + '-' + datePart[0] + 'T' + time;
  }
}
