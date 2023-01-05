import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../common/crud-types';
import {ListFilterElementInterface, ListFilterFieldInterface} from '../../../../../shared/components/list-filter/list-filter.model';
import {ListFilterTypeEnum} from '../../../../../shared/components/list-filter/list-filter.enum';
import {select, Store} from '@ngrx/store';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-balance-list',
  templateUrl: './list.component.html'
})
export class ListComponent implements OnInit {
  type = CrudType.ProductStock;
  filterFields: ListFilterFieldInterface[][];

  constructor(
    protected store: Store<CrudState>,
  ) {
  }

  ngOnInit() {
    const stocksAttributes: ListFilterElementInterface = {options: []};

    this.filterFields = [
      [
        {
          mutableSearchType: CrudType.ReferenceStock,
          type: ListFilterTypeEnum.select,
          prop: 'stock.id',
          head: {value: 'Склад'},
          attributes: stocksAttributes,
        }
      ],
    ];
    this.store.dispatch(new LoadGetListAction({type: CrudType.ReferenceStock}));
    this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceStock}))
      .subscribe(data => stocksAttributes.options = data);

  }

}
