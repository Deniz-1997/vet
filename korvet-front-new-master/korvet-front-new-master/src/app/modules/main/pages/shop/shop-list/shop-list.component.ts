import { Component, OnInit } from '@angular/core';

import {Store} from '@ngrx/store';
import { CrudType } from 'src/app/common/crud-types';
import { SearchModels } from 'src/app/models/search.models';
import { ListFilterFieldInterface } from 'src/app/modules/shared/components/list-filter/list-filter.model';
import { ListFilterTypeEnum } from 'src/app/modules/shared/components/list-filter/list-filter.enum';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({
  selector: 'app-shop-list',
  templateUrl: './shop-list.component.html',
  styleUrls: ['./shop-list.component.css']
})
export class ShopListComponent implements OnInit {
  type = CrudType.ShopOrders;
  filterFields: ListFilterFieldInterface[][];
  c = '#';
  d = 'demo';

  constructor(
    protected store: Store<CrudState>
  ) {
  }

  ngOnInit(): void {
    this.filterFields = [
      [
        {
          mutableSearchType: CrudType.ShopOrders,
          type: ListFilterTypeEnum.date,
          prop: '>=date',
          head: {value: 'Дата продажи от'}
        },
        {
          mutableSearchType: CrudType.ShopOrders,
          type: ListFilterTypeEnum.date,
          prop: '<=date',
          head: {value: 'до'}
        },
        {
          mutableSearchType: CrudType.ShopOrders,
          type: ListFilterTypeEnum.autocomplete,
          prop: 'unit',
          field: 'name',
          head: {value: 'Клиника'},
          attributes: {
            optionsType: CrudType.ReferenceUnit
          },
        },
        {
          mutableSearchType: CrudType.ShopOrders,
          type: ListFilterTypeEnum.autocomplete,
          prop: 'stock',
          field: 'name',
          head: {value: 'Склад'},
          attributes: {
            optionsType: CrudType.ReferenceStock
          },
        },
      ],
    ];
  }
}
