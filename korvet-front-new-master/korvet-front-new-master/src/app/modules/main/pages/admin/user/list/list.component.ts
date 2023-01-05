import {Component, OnInit} from '@angular/core';
import {ListFilterElementInterface, ListFilterFieldInterface} from '../../../../../shared/components/list-filter/list-filter.model';
import {ListFilterTypeEnum} from '../../../../../shared/components/list-filter/list-filter.enum';
import {select, Store} from '@ngrx/store';
import {CrudType} from 'src/app/common/crud-types';
import { AuthService } from 'src/app/services/auth.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './list.component.html'})

export class ListComponent implements OnInit {
  type = CrudType.User;
  filterFields: ListFilterFieldInterface[][];
  pageSize = 20;
  groupTypesAttributes: ListFilterElementInterface = {options: []};
  c = '#';
  g = '22';
  d = 'demo';

  constructor(
    protected store: Store<CrudState>,
    public authService: AuthService,
  ) {
  }

  ngOnInit() {
    this.store.dispatch(new LoadGetListAction({type: CrudType.Group, params: {fields: {0: 'id', 2: 'name'}}}));
    this.store.pipe(select(getCrudModelData, {type: CrudType.Group}))
      .subscribe(data => this.groupTypesAttributes.options = data);

    this.filterFields = [
      [
        {
          type: ListFilterTypeEnum.select,
          prop: 'groups.id',
          head: {value: 'Тип пользователя'},
          attributes: this.groupTypesAttributes,
        },
        {
          type: ListFilterTypeEnum.select,
          head: {value: 'Активность'},
          prop: 'status',
          attributes: {
            options: [
              {value: 1, name: 'Активна'},
              {value: 0, name: 'Неактивна'}
            ]
          }
        }
      ],
    ];
  }

  switchUser(user) {
    localStorage.setItem("switchUser", user);
    localStorage.removeItem('sidenav');
    window.location.href='/';
  }
}
