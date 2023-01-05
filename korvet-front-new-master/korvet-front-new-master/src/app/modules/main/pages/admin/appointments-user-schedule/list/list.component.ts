import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../common/crud-types';
import {ListFilterElementInterface, ListFilterFieldInterface} from '../../../../../shared/components/list-filter/list-filter.model';
import {select, Store} from '@ngrx/store';
import {filter, take} from 'rxjs/operators';
import {ListFilterTypeEnum} from '../../../../../shared/components/list-filter/list-filter.enum';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './list.component.html'})

export class ListComponent implements OnInit {

  type = CrudType.UserSchedule;

  filterFields: ListFilterFieldInterface[][];
  cashiersAttributes: ListFilterElementInterface = {options: []};
  c = '#';
  g = '22';
  d = 'demo';

  constructor(
    protected store: Store<CrudState>,
  ) {
  }

  ngOnInit() {

    this.store.pipe(select(getCrudModelData, {type: CrudType.User}))
      .pipe(
        filter(data => !!data && !!data.length),
        take(1)
      )
      .subscribe(
        data => {
          if (data && data.length) {
            this.cashiersAttributes.options = data.reduce(
              (acc, user) => user.id ? [...acc, {id: user.id, name: user.getFullName()}] : acc, []);
          }
        }
      );

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.User,
      params: {
        order: {surname: 'ASC'},
        offset: 0,
        limit: 500
      }
    }));

    this.filterFields = [
      [
        {
          type: ListFilterTypeEnum.multiSelect,
          head: {value: 'Врач'},
          prop: 'employee',
          attributes: this.cashiersAttributes,
        },
      ],
    ];
  }

  getFullName(employee: { name?: string; surname: string; patronymic?: string; }): string {
    return (((employee.surname).trim() + ' ' + (employee.name + ' ' + employee.patronymic).trim()).trim());
  }

}
