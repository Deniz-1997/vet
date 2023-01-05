import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {ListFilterFieldInterface} from '../../../../../../shared/components/list-filter/list-filter.model';
import {Urls} from '../../../../../../../common/urls';
import {Store} from '@ngrx/store';
import {HttpClient} from '@angular/common/http';
import {ListFilterTypeEnum} from '../../../../../../shared/components/list-filter/list-filter.enum';
import {StocksListModels} from '../../../../../../../models/product/stocks.list.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({
  selector: 'app-product-expense-list',
  templateUrl: './list.component.html'
})
export class ListComponent extends StocksListModels implements OnInit {

  type = CrudType.ProductExpense;
  filterFields: ListFilterFieldInterface[][];
  downloadUrl = Urls.apiDocumentExpense;
  c = '#';
  d = 'demo';

  constructor(
    protected store: Store<CrudState>,
    protected http: HttpClient
  ) {
    super(http);
    this.getFiltersAttributes();
  }

  ngOnInit() {
    this.filterFields = [
      [
        {
          type: ListFilterTypeEnum.autocomplete,
          prop: 'stock',
          field: 'name',
          head: {value: 'Склад'},
          attributes: {
            optionsType: CrudType.ReferenceStock
          },
        },
        {
          type: ListFilterTypeEnum.select,
          head: {value: 'Статус'},
          prop: 'state',
          attributes: this.statusAttributes,
        },
      ],
    ];
  }

}
