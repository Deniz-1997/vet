import {Component, OnInit} from '@angular/core';
import {Observable} from 'rxjs';
import {ActionModel} from '../../../../../models/action/action.models';
import {select, Store} from '@ngrx/store';
import {CrudType} from '../../../../../common/crud-types';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData, getCrudModelGetListLoading} from 'src/app/api/api-connector/crud/crud.selectors';
import {AuthState} from 'src/app/api/auth/auth.reducer';

@Component({templateUrl: './html.component.html'})

export class MainComponent implements OnInit {

  loading$: Observable<boolean>;
  type = CrudType.Action;
  catalog$: Observable<ActionModel[]>;
  c = '#';
  g = '22';
  d = 'demo';

  constructor(private store: Store<AuthState>) {
  }

  ngOnInit() {

    this.catalog$ = this.store.pipe(select(getCrudModelData, {type: this.type}));
    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: {
        filter: {
          groups: {code: 'CATALOG'},
        },
        order: {sort: 'ASC'},
        limit: 100
      }
    }));

    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: this.type}));

  }

}
