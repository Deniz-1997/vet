import {Component, OnInit} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {Observable} from 'rxjs';
import {ActionModel} from '../../../../../models/action/action.models';
import {CrudType} from 'src/app/common/crud-types';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData, getCrudModelGetListLoading} from 'src/app/api/api-connector/crud/crud.selectors';
import {AuthState} from 'src/app/api/auth/auth.reducer';

@Component({templateUrl: './html.component.html'})

export class HtmlComponent implements OnInit {

  loading$: Observable<boolean>;
  type = CrudType.Action;
  catalog$: Observable<ActionModel[]>;

  constructor(private store: Store<AuthState>) {
  }

  ngOnInit() {

    this.catalog$ = this.store.pipe(select(getCrudModelData, {type: this.type}));
    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: {
        filter: {
          groups: {code: 'LEFT_MENU'},
          parent: {code: 'admin'}
        },
        order: {sort: 'ASC', name: 'ASC'},
      }
    }));

    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: this.type}));

  }
}
