import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {ListFilterElementInterface, ListFilterFieldInterface} from '../../../../../../shared/components/list-filter/list-filter.model';
import {ListFilterTypeEnum} from '../../../../../../shared/components/list-filter/list-filter.enum';
import {select, Store} from '@ngrx/store';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-product-history-list',
  templateUrl: './list.component.html'
})
export class ListComponent implements OnInit {

  type = CrudType.DocumentHistory;
  filterFields: ListFilterFieldInterface[][];
  c = '#';
  d = 'demo';

  constructor(
    protected store: Store<CrudState>,
  ) {
  }

  ngOnInit() {
    const stocksAttributes: ListFilterElementInterface = {options: []};
    const documentOperationAttributes: ListFilterElementInterface = {options: []};
    this.filterFields = [
      [
        {
          mutableSearchType: CrudType.Appointment,
          type: ListFilterTypeEnum.date,
          prop: '>=date',
          head: {value: 'Дата от'}
        },
        {
          mutableSearchType: CrudType.Appointment,
          type: ListFilterTypeEnum.date,
          prop: '<=date',
          head: {value: 'до'}
        },
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
          mutableSearchType: CrudType.ReferenceStock,
          type: ListFilterTypeEnum.select,
          prop: 'operationType',
          head: {value: 'Тип операции'},
          attributes: documentOperationAttributes,
        },
      ]
    ];
    this.store.dispatch(new LoadGetListAction({type: CrudType.ReferenceStock}));
    this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceStock}))
      .subscribe(data => stocksAttributes.options = data);

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Enum,
      params: {
        filter: {
          id: [
            'DocumentOperationTypeEnum',
          ]
        }
      },
      onSuccess: (res) => {
        documentOperationAttributes.options = res.response[0].items;
      }
    }));
  }

}
