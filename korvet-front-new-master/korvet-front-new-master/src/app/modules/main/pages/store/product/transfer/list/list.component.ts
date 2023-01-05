import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {ListFilterFieldInterface} from '../../../../../../shared/components/list-filter/list-filter.model';
import {Store} from '@ngrx/store';
import {ListFilterTypeEnum} from '../../../../../../shared/components/list-filter/list-filter.enum';
import {StocksListModels} from '../../../../../../../models/product/stocks.list.models';
import {HttpClient} from '@angular/common/http';
import {Urls} from '../../../../../../../common/urls';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({
  selector: 'app-product-transfer-list',
  templateUrl: './list.component.html'
})
export class ListComponent extends StocksListModels implements OnInit {
  type = CrudType.ProductTransfer;
  filterFields: ListFilterFieldInterface[][];
  downloadUrl = Urls.apiDocumentTransfer;
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
          prop: 'stockFrom',
          field: 'name',
          head: {value: 'Склад отправитель'},
          attributes: {
            optionsType: CrudType.ReferenceStock
          },
        },
        {
          type: ListFilterTypeEnum.autocomplete,
          prop: 'stockTo',
          field: 'name',
          head: {value: 'Склад получатель'},
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
