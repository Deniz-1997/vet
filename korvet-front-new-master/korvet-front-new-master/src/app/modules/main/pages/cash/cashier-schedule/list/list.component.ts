import {Component, OnDestroy, OnInit} from '@angular/core';
import {ListFilterTypeEnum} from '../../../../../shared/components/list-filter/list-filter.enum';
import {ListFilterElementInterface, ListFilterFieldInterface} from '../../../../../shared/components/list-filter/list-filter.model';
import {select, Store} from '@ngrx/store';
import {CrudType} from 'src/app/common/crud-types';
import {filter, take} from 'rxjs/operators';
import {Subscription} from 'rxjs';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './list.component.html'})

export class ListComponent implements OnInit, OnDestroy {
  type = CrudType.CashierSchedule;
  c = '#';
  d = 'demo';
  filterFields: ListFilterFieldInterface[][];
  cashiersAttributes: ListFilterElementInterface = {options: []};
  cashRegistersAttributes: ListFilterElementInterface = {options: []};

  private subscription: Subscription;

  constructor(
    protected store: Store<CrudState>,
  ) {
  }

  ngOnInit() {
    this.subscription = new Subscription();
    this.subscription.add(
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
        )
    );

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.User,
      params: {
        order: {surname: 'ASC'},
        offset: 0,
        limit: 500
      }
    }));

    this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceCashRegister})).subscribe(
      data => this.cashRegistersAttributes.options = data
    );

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceCashRegister,
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
          head: {value: 'Кассир'},
          prop: 'cashier',
          attributes: this.cashiersAttributes,
        },
        {
          type: ListFilterTypeEnum.multiSelect,
          head: {value: 'ККМ'},
          prop: 'cashRegister',
          attributes: this.cashRegistersAttributes
        }
      ],
    ];
  }

  ngOnDestroy(): void {
    this.subscription.unsubscribe();
  }

  getFullName(cashier: { name?: string; surname: string; patronymic?: string; }): string {
    return (((cashier.surname).trim() + ' ' + (cashier.name + ' ' + cashier.patronymic).trim()).trim());
  }

}
