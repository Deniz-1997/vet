import {Component, OnInit} from '@angular/core';
import {Observable} from 'rxjs';
import {ReferenceFileTypeModel} from '../../../../../../../models/reference/reference.file.type.models';
import {select, Store} from '@ngrx/store';
import {filter, map} from 'rxjs/operators';
import {CrudType} from '../../../../../../../common/crud-types';
import {EditComponent} from '../edit/edit.component';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrud} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './list.component.html'})

export class ListComponent implements OnInit {

  type = CrudType.ReferenceFileType;
  items$: Observable<ReferenceFileTypeModel[]>;
  component = EditComponent;
  code = 'file-type';

  constructor(
    private store: Store<CrudState>,
  ) {
    this.items$ = store.pipe(
      select(getCrud),
      filter(state => !!state[this.type] && !!state[this.type].data),
      map(state => Object.keys(state[this.type].data)
        .map(key => state[this.type].data[key])
      )
    );
  }

  ngOnInit() {
    this.store.dispatch(new LoadGetListAction({type: this.type, params: {order: {id: 'DESC'}}}));
  }
}
