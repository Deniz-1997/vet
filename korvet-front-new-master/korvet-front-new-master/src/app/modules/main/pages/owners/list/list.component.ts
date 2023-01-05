import {Component} from '@angular/core';
import {Store} from '@ngrx/store';
import {SearchModels} from '../../../../../models/search.models';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({templateUrl: './list.component.html'})

export class ListComponent extends SearchModels {
  type = CrudType.Owner;
  model = CrudType.Owner;
  c = '#';
  d = 'demo';


  constructor(
    protected store: Store<CrudState>
  ) {
    super();
    this.store.dispatch(new LoadGetListAction({type: CrudType.ReferencePetType, params: {order: {sort: 'ASC', name: 'ASC'}}}));
  }
}
