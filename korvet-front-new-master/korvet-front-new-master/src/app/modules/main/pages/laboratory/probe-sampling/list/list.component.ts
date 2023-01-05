import { Component, OnInit } from '@angular/core';

import {Store} from '@ngrx/store';
import { CrudType } from 'src/app/common/crud-types';
import { ListFilterFieldInterface } from 'src/app/modules/shared/components/list-filter/list-filter.model';
import { ListFilterTypeEnum } from 'src/app/modules/shared/components/list-filter/list-filter.enum';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({
  selector: 'app-probe-sampling-list',
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.css']
})
export class ProbeSamplingListComponent implements OnInit {
  type = CrudType.ProbeSampling;
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
          head: {value: 'Дата от'}
        },
        {
          mutableSearchType: CrudType.ShopOrders,
          type: ListFilterTypeEnum.date,
          prop: '<=date',
          head: {value: 'до'}
        },
        {
          mutableSearchType: CrudType.Pet,
          type: ListFilterTypeEnum.autocomplete,
          prop: 'pet',
          field: 'name',
          head: {value: 'Животное'},
          attributes: {
            optionsType: CrudType.Pet
          },
        }
      ],
    ];
  }
}
