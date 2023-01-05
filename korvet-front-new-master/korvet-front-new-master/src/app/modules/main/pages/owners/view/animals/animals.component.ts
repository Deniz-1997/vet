import {Component, OnInit} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {Observable} from 'rxjs';
import {ViewService} from '../view.service';
import {PetToOwnerModel} from '../../../../../../models/pet/pet-to-owner.models';
import {PersonType} from '../../../../../../utils/person-type';
import {ActivatedRoute, Router} from '@angular/router';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadAppendListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData, getCrudModelGetListLoading, getCrudModelTotalCount, getCrudModelAppendListLoading, getCrudModelStoreId} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './animals.component.html'})

export class AnimalsComponent implements OnInit {

  petsToOwner$: Observable<PetToOwnerModel[]>;
  loading$: Observable<boolean>;
  appendLoading$: Observable<boolean>;
  totalCount$: Observable<number>;
  offset = 0;
  limit = 40;
  c = '#';
  d = 'demo';

  constructor(
    private store: Store<CrudState>,
    private service: ViewService,
    private router: Router,
    private route: ActivatedRoute,
  ) {
    this.petsToOwner$ = store.pipe(select(getCrudModelData, {type: CrudType.PetToOwner}));
    this.loading$ = store.pipe(select(getCrudModelGetListLoading, {type: CrudType.PetToOwner}));
    this.totalCount$ = store.pipe(select(getCrudModelTotalCount, {type: CrudType.PetToOwner}));
    this.appendLoading$ = store.pipe(select(getCrudModelAppendListLoading, {type: CrudType.PetToOwner}));
    store.pipe(select(getCrudModelStoreId, {type: CrudType.Owner, params: service.id}))
      .subscribe(owner => {
        if (owner && owner.type === PersonType.INDIVIDUAL_PERSON) {
          this.router.navigate(['../'], {relativeTo: this.route.parent}).then();
        }
      });
  }

  ngOnInit() {
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.PetToOwner,
      params: {
        order: {id: 'DESC'},
        limit: this.limit,
        offset: this.offset,
        filter: {owner: {id: this.service.id}}
      }
    }));
  }

  appendList(offset, limit): void {
    this.store.dispatch(new LoadAppendListAction({
      type: CrudType.PetToOwner,
      params: {order: {id: 'DESC'}, offset: offset, limit: limit}
    }));
  }
}
