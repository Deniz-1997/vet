import {Component, OnInit} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {Observable} from 'rxjs';
import {ActionModel} from '../../../../../models/action/action.models';
import {CrudType} from 'src/app/common/crud-types';
import {getCrudModelData, getCrudModelGetListLoading} from '../../../../../api/api-connector/crud/crud.selectors';
import {AuthState} from '../../../../../api/auth/auth.reducer';
import {LoadGetListAction} from '../../../../../api/api-connector/crud/crud.actions';

@Component({templateUrl: './html.component.html'})

export class HtmlComponent implements OnInit {

  loading$: Observable<boolean>;
  type = CrudType.Action;
  catalog$: Observable<Array<ActionModel>>;
  displayedColumns = ['name'];

  constructor(private store: Store<AuthState>) {
  }

  ngOnInit(): void {
    this.catalog$ = this.store.pipe(select(getCrudModelData, {type: this.type}));
    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: {
        filter: {
          groups: {code: 'LEFT_MENU'},
          parent: {code: 'admin'}
        },
        order: {sort: 'ASC', name: 'ASC'},
      },
      onSuccess: response => {}
    }));

    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: this.type}));

  }
}
