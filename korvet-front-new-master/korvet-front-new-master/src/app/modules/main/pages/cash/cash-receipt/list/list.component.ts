import {Component, OnInit} from '@angular/core';
import {CashService} from '../../cash.service';
import {select, Store} from '@ngrx/store';
import {CrudType} from 'src/app/common/crud-types';
import {ListFilterTypeEnum} from '../../../../../shared/components/list-filter/list-filter.enum';
import {ListFilterElementInterface, ListFilterFieldInterface} from '../../../../../shared/components/list-filter/list-filter.model';
import {EnumModel} from '../../../../../../models/enum .models';
import {ModalConfirmComponent} from '../../../../../shared/components/modal-confirm/modal-confirm.component';
import {MatDialog} from '@angular/material/dialog';
import {ModalConfirmSumComponent} from '../../../../../shared/components/modal-confirm-sum/modal-confirm-sum.component';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadGetAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './list.component.html'})

export class ListComponent implements OnInit {
  type = CrudType.CashReceipt;
  filterFields: ListFilterFieldInterface[][];
  CashReceiptTypeEnum: EnumModel;
  c = '#';
  d = 'demo';

  cashiersAttributes: ListFilterElementInterface = {options: []};
  cashRegistersAttributes: ListFilterElementInterface = {options: []};
  cashReceiptTypeEnumAttributes: ListFilterElementInterface = {options: []};
  fiscalReceiptStateEnumAttributes: ListFilterElementInterface = {options: []};
  paymentTypeEnumAttributes: ListFilterElementInterface = {options: []};

  constructor(
    protected store: Store<CrudState>,
    public cashService: CashService,
    private dialog: MatDialog,
  ) {
  }

  ngOnInit() {
    this.store.pipe(select(getCrudModelData, {type: CrudType.User}))
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
          mutableSearchType: CrudType.Appointment,
          type: ListFilterTypeEnum.date,
          prop: '>=createdAt',
          head: {value: 'Дата создания от'}
        },
        {
          mutableSearchType: CrudType.Appointment,
          type: ListFilterTypeEnum.date,
          prop: '<=createdAt',
          head: {value: 'до'}
        },
        {
          mutableSearchType: this.type,
          type: ListFilterTypeEnum.select,
          prop: 'paymentType',
          head: {value: 'Метод оплаты'},
          attributes: this.paymentTypeEnumAttributes
        },
        {
          type: ListFilterTypeEnum.multiSelect,
          head: {value: 'ККМ'},
          prop: 'cashRegister',
          attributes: this.cashRegistersAttributes
        },
      ],
      [
        {
          type: ListFilterTypeEnum.multiSelect,
          head: {value: 'Кассир'},
          prop: 'cashier',
          attributes: this.cashiersAttributes,
        },
        {
          mutableSearchType: CrudType.ReferenceStock,
          type: ListFilterTypeEnum.select,
          prop: 'type',
          head: {value: 'Тип чека'},
          attributes: this.cashReceiptTypeEnumAttributes,
        },
        {
          mutableSearchType: CrudType.ReferenceStock,
          type: ListFilterTypeEnum.select,
          prop: 'fiscal.state',
          head: {value: 'Статус'},
          attributes: this.fiscalReceiptStateEnumAttributes,
        }
      ]
    ];

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Enum,
      params: {
        filter: {
          id: [
            'CashReceiptTypeEnum',
            'FiscalReceiptStateEnum',
            'PaymentTypeEnum'
          ]
        }
      },
      onSuccess: (res) => {
        this.cashReceiptTypeEnumAttributes.options = res.response[0].items;
        this.fiscalReceiptStateEnumAttributes.options = res.response[1].items;
        this.paymentTypeEnumAttributes.options = res.response[2].items;
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
        limit: 500,
        filter: {'active': true}
      }
    }));

  }

  onBreakCheck(item) {

    if (item.paymentType.code === 'ELECTRONICALLY') {
      const dialogRef = this.dialog.open(ModalConfirmComponent, {
        data: {
          head: 'Подтвердите, что чек POS-терминала прошел',
          headComment: '(операция по карте провелась)',
          actions: [
            {
              class: 'btn-st btn-st--left btn-st--gray',
              action: false,
              title: 'Отмена'
            },
            {
              class: 'btn-st btn-st--right',
              action: true,
              title: 'Да'
            },
          ],
        }
      });

      dialogRef.afterClosed().subscribe((result: boolean) => {
        if (result) {
          this.onCashRegisterRegister(item.id);
        }
      });
    } else {
      this.onCashRegisterRegister(item.id);
    }
  }

  onCashRegisterRegister(id) {
    this.cashService.onCashRegisterRegister(id, (res) => {
      if (res.response.correlationId) {
        this.cashService.getAsyncResult(res.response.correlationId, (data) => {
          if (data && data.asyncStatus === 'DONE') {
            this.store.dispatch(new LoadGetAction({type: this.type, params: id}));
          }
        });
      }
    });
  }

  onReturn(item) {
    const dialogRef = this.dialog.open(ModalConfirmSumComponent, {
      data: {
        head: `Вы уверены, что хотите сделать возврат?`,
        body: 'Действие необратимо.',
        actions: [
          {
            class: 'btn-st btn-st--left btn-st--blue',
            action: false,
            title: 'Отмена'
          },
          {
            class: 'btn-st btn-st--right btn-st--red',
            action: true,
            title: 'Возврат'
          },
        ],
        numbersTitle: 'Введите сумму чисел для продолжения',
      },
    });

    dialogRef.afterClosed().subscribe((result: boolean) => {
      if (result) {
        this.cashService.onReturn(item.id);
      }
    });
  }
}