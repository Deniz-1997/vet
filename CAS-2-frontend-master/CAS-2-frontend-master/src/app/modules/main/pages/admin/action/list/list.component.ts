import {Component, OnInit} from '@angular/core';
import {CrudType} from 'src/app/common/crud-types';
import {select, Store} from '@ngrx/store';
import {EditComponent} from '../edit/edit.component';
import {ListFilterElementInterface, ListFilterFieldInterface} from 'src/app/modules/shared/components/list/list-filter/list-filter.model';
import {ListFilterTypeEnum} from 'src/app/modules/shared/components/list/list-filter/list-filter.enum';
import {getCrudModelData} from '../../../../../../api/api-connector/crud/crud.selectors';
import {CrudState} from '../../../../../../api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from '../../../../../../api/api-connector/crud/crud.actions';

@Component({templateUrl: './list.component.html', styleUrls: ['./list.component.css']})

export class ListComponent implements OnInit {
  type = CrudType.Action;
  component = EditComponent;
  code = 'action';
  filterFields: Array<Array<ListFilterFieldInterface>>;

  constructor(
    private store: Store<CrudState>,
  ) {
  }

  ngOnInit(): void {
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
