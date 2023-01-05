import {Component, OnInit} from '@angular/core';
import {ListFilterTypeEnum} from '../../../../../shared/components/list-filter/list-filter.enum';
import {ListFilterElementInterface, ListFilterFieldInterface} from '../../../../../shared/components/list-filter/list-filter.model';
import {select, Store} from '@ngrx/store';
import {EnumModel} from '../../../../../../models/enum .models';
import {CrudType} from 'src/app/common/crud-types';
import {MatDialog} from '@angular/material/dialog';
import {DialogExportComponent} from '../dialog-export/dialog-export.component';
import {AsyncStatus} from '../../../cash/cash.service';
import {Urls} from '../../../../../../common/urls';
import {HttpClient} from '@angular/common/http';
import {NotifyService} from '../../../../../../services/notify.service';
import {Router} from '@angular/router';
import {SettingsService} from '../../../../../../services/settings.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-product-list',
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.css']
})

export class ListComponent implements OnInit {
  type = CrudType.ReferenceProduct;
  loader = false;

  filterFields: ListFilterFieldInterface[][];
  PaymentObjectEnum: EnumModel;

  constructor(
    protected store: Store<CrudState>,
    private dialog: MatDialog,
    private http: HttpClient,
    protected notify: NotifyService,
    protected router: Router,
    public setting: SettingsService,
  ) {
  }

  ngOnInit() {
    const stocksAttributes: ListFilterElementInterface = {options: []};
    const paymentObjectAttributes: ListFilterElementInterface = {options: []};
    this.filterFields = [
      [
        {
          type: ListFilterTypeEnum.autocomplete,
          prop: 'productStock.stock',
          field: 'name',
          head: {value: 'Склад'},
          attributes: {
            optionsType: CrudType.ReferenceStock
          },
        },
        {
          mutableSearchType: CrudType.ReferenceStock,
          type: ListFilterTypeEnum.select,
          prop: 'paymentObject',
          head: {value: 'Предмет расчета'},
          attributes: paymentObjectAttributes,
        },
        {
          mutableSearchType: CrudType.ReferenceStock,
          type: ListFilterTypeEnum.select,
          head: {value: 'Наличие'},
          prop: 'existQuantity',
          attributes: {
            options: [
              {value: 1, name: 'Есть в наличии', sign: '>'},
              {value: 0, name: 'Нет в наличии', sign: '<'}
            ]
          }
        },
      ],
      [
        {
          mutableSearchType: CrudType.Pet,
          type: ListFilterTypeEnum.select,
          head: {value: 'Активность'},
          prop: 'active',
          attributes: {
            options: [
              {value: 0, name: 'Не активно', sign: '>'},
              {value: 1, name: 'Активно', sign: '<'}
            ]
          }
        },
        {
          mutableSearchType: CrudType.ReferenceStock,
          type: ListFilterTypeEnum.select,
          head: {value: 'Цена'},
          prop: 'existPrice',
          attributes: {
            options: [
              {value: 1, name: 'С ценой', sign: '>'},
              {value: 0, name: 'Без цены', sign: '<'}
            ]
          }
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
            'PaymentObjectEnum',
          ]
        }
      },
      onSuccess: (res) => {
        paymentObjectAttributes.options = res.response[0].items;
      }
    }));
  }

  importTo(url) {
    this.http.post<AsyncStatus>(url === 1 ? Urls.apiImportTo1 : (url === 2 ? Urls.apiImportTo2 : Urls.apiImportTo3), '').subscribe(
      item => {
        if (item && item.status) {
          this.notify.handleMessage('Импорт прошел успешно', 'success');

          if (item && item.response && item.response.length > 0) {
            this.router.navigate(['/store/ftp-history']).then();
          }
        }
        this.loader = false;
      },
      () => {
        this.loader = false;
      },
      () => this.loader = false
    );
  }

  exportTo() {
    this.dialog.open(DialogExportComponent, {});
  }

}
