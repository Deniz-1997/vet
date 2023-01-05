import {Component, OnInit} from '@angular/core';
import {CrudType} from 'src/app/common/crud-types';
import {ListFilterElementInterface, ListFilterFieldInterface} from '../../../../../../shared/components/list-filter/list-filter.model';
import {ListFilterTypeEnum} from '../../../../../../shared/components/list-filter/list-filter.enum';
import {select, Store} from '@ngrx/store';
import {EditComponent} from '../edit/edit.component';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './list.component.html', styleUrls: ['./list.component.css']})

export class ListComponent implements OnInit {
  type = CrudType.Action;
  component = EditComponent;
  code = 'action';
  filterFields: ListFilterFieldInterface[][];

  constructor(
    private store: Store<CrudState>,
  ) {
  }

  ngOnInit() {
    this.store.dispatch(new LoadGetListAction({type: CrudType.ActionGroup}));
    const groupsAttributes: ListFilterElementInterface = {options: []};
    this.filterFields = [
      [
        {
          type: ListFilterTypeEnum.select,
          prop: 'groups.id',
          head: {value: 'Группа действий'},
          attributes: groupsAttributes,
        }
      ]
    ];
    this.store.pipe(select(getCrudModelData, {type: CrudType.ActionGroup}))
      .subscribe(data => groupsAttributes.options = data);
  }

}
