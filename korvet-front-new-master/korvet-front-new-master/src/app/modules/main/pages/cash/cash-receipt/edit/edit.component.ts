import {Component, OnDestroy, OnInit} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {BehaviorSubject, Observable, Subject} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {FormArray, FormControl, FormGroup, Validators} from '@angular/forms';
import {CashReceiptModel} from '../../../../../../models/cash/cash.receipt.models';
import {ReferenceCashRegisterModel} from '../../../../../../models/reference/reference.cash.register.models';
import {ModalConfirmComponent} from '../../../../../shared/components/modal-confirm/modal-confirm.component';
import {MatDialog} from '@angular/material/dialog';
import {EnumModel} from '../../../../../../models/enum .models';
import {debounceTime, distinctUntilChanged, takeUntil} from 'rxjs/operators';
import {CashService} from '../../cash.service';
import {ReferenceOrganizationModel} from '../../../../../../models/reference/reference.organization.models';
import {AuthService} from '../../../../../../services/auth.service';
import {CrudType} from 'src/app/common/crud-types';
import {ModalConfirmSumComponent} from "../../../../../shared/components/modal-confirm-sum/modal-confirm-sum.component";
import {ApiConnectorService} from 'src/app/api/api-connector/api-connector.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadGetAction, LoadPatchAction, LoadCreateAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';
import {AppointmentInterface, AppointmentModel} from 'src/app/models/appointment/appointment.models';
import {ShopOrderModel} from 'src/app/models/shop/shop.order.models';

declare var $: any;

@Component({selector: 'app-edit', templateUrl: './edit.component.html', styleUrls: ['./edit.component.css']})

export class EditComponent extends ReferenceItemModels implements OnInit, OnDestroy {
  public destroy$ = new Subject<any>();
  crudType = CrudType;
  public referenceCashRegisterItems: Observable<ReferenceCashRegisterModel[]>;
  TaxationTypeEnum: EnumModel[];
  CashReceiptTypeEnum: EnumModel[];
  ReceiptDeliveryTypeEnum: EnumModel[];
  PaymentMethodEnum: EnumModel[];
  PaymentTypeEnum: EnumModel[];
  CorrectionTypeEnum: EnumModel[];
  PaymentObjectEnum: EnumModel[];
  VatRateEnum: EnumModel[];
  ProductCodeTypeEnum: EnumModel[];
  idCashReceipt;
  currentCashRegister;
  referenceProductItems$: Observable<ReferenceOrganizationModel[]>;
  referenceProductItems: ReferenceOrganizationModel[];
  currentTotal;
  closeWithCapture = true;
  currentVatRateCode = new Subject<string>();
  defaultVatRateCode = 'VAT_20';
  loading = new BehaviorSubject(false);
  appointment: AppointmentModel = new AppointmentModel();
  shopOrder: ShopOrderModel = new ShopOrderModel();
  productFields = {
    0: 'id',
    1: 'name',
    2: 'measure',
    3: 'productCode',
    4: 'paymentObject',
    5: 'vatRate',
    6: 'price',
    7: 'quantity',
    8: 'productStock'
  };
  productStockFields = {0: 'id', 1: 'name', 2: 'price', 3: 'measure', 4: 'quantity', 5: 'productStock'};
  editableItems = false;
  protected listNavigate = ['cash', 'cash-receipt'];
  protected titleName = 'Кассовый чек';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    private dialog: MatDialog,
    public cashService: CashService,
    protected authService: AuthService,
    protected crud: ApiConnectorService,
  ) {
    super(CrudType.CashReceipt, CashReceiptModel);

    this.referenceProductItems$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceProduct}));
    this.referenceProductItems$.subscribe(item => this.referenceProductItems = item);
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceProduct,
      params: {
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));

    this.referenceCashRegisterItems = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceCashRegister}));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceCashRegister,
      params: {
        fields: {0: 'id', 1: 'name'},
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Enum,
      params: {
        filter: {
          id: [
            'TaxationTypeEnum',
            'CashReceiptTypeEnum',
            'ReceiptDeliveryTypeEnum',
            'PaymentMethodEnum',
            'PaymentTypeEnum',
            'CorrectionTypeEnum',
            'PaymentObjectEnum',
            'VatRateEnum',
            'ProductCodeTypeEnum'
          ]
        }
      },
      onSuccess: (res) => {
        res.response.map(
          item => {
            this[item.id] = item.items;
          }
        );
      }
    }));
  }

  getReceiptsBase(
    name?: string,
    product?: any,
    price?: number,
    priceWithCharge?: number,
    quantity?: number,
    amount?: number,
    measure?: string,
    paymentObjectCode?: string,
    productCode?: string,
    vatRateCode?: string,
    gtin?: string,
    serial?: string,
    id?: number,
    stockId?: number,
    stockName?: string,
  ) {
    const balance = product && product.balance ? product.balance : 0;
    const itemFormGroup = new FormGroup({
      id: new FormControl(id || null),
      name: new FormControl(name ? name : '', [Validators.required]),
      product: new FormControl(product ? product : null),
      price: new FormControl(price ? price : 0, [Validators.required, Validators.min(0.1)]),
      priceWithCharge: new FormControl(priceWithCharge ? priceWithCharge : 0, [Validators.required, Validators.min(0)]),
      quantity: new FormControl(quantity ? quantity : (balance === '-' || balance > 0 ? 0 : 1), []),
      amount: new FormControl(amount ? amount : null, [Validators.required]),
      measure: new FormControl(measure ? measure : 'шт.'),
      balance: new FormControl({value: balance, disabled: true}),
      paymentObject: new FormGroup({
        code: new FormControl(paymentObjectCode ? paymentObjectCode : 'COMMODITY', [Validators.required]),
      }),
      productCode: new FormGroup({
        type: new FormGroup({
          code: new FormControl(productCode ? productCode : null)
        }),
        gtin: new FormControl(gtin ? gtin : null, [Validators.minLength(14)]),
        serial: new FormControl(serial ? serial : null, [Validators.minLength(13)]),
      }),
      vatRate: new FormGroup({
        code: new FormControl({
          value: (vatRateCode ? vatRateCode : this.defaultVatRateCode),
          disabled: this.isVatRateCode()
        }),
      }),
      stock: new FormGroup({
        id: new FormControl(stockId ? stockId : null),
        name: new FormControl(stockName ? stockName : null),
      }),
      productStock: new FormControl([])
    });

    itemFormGroup.get('product')
      .valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .pipe(
        debounceTime(500),
        distinctUntilChanged()
      )
      .subscribe(value => {
        if (value instanceof Object) {
          const productItem = value;
          itemFormGroup.get('name').setValue(value.name);
          itemFormGroup.get('measure').setValue(value.measure);
          itemFormGroup.get('price').setValue(value.price);
          itemFormGroup.get('productCode.type.code').setValue(value.productCode.type.code ? value.productCode.type.code : null);
          itemFormGroup.get('productCode.gtin').setValue(value.productCode.gtin);
          itemFormGroup.get('productCode.serial').setValue(value.productCode.serial);
          itemFormGroup.get('paymentObject.code').setValue(value.paymentObject.code);
          itemFormGroup.get('balance').setValue(value.quantity);
          itemFormGroup.get('quantity').setValidators([
            Validators.required,
            Validators.min(1),
            ...itemFormGroup.get('paymentObject.code').value !== 'SERVICE' && !id ?
              [Validators.max(value.quantity)] : [],
          ]);
          if (this.defaultVatRateCode !== 'NONE') {
            itemFormGroup.get('vatRate.code').setValue(productItem.vatRate.code);
          }

          if (value.productStock) {
            if (itemFormGroup.get('stock')) {
              itemFormGroup.get('stock.id').setValue(value.productStock[0].stock.id);
              itemFormGroup.get('stock.name').setValue(value.productStock[0].stock.name);
            }
            itemFormGroup.get('balance').setValue(value.productStock[0].quantity);
            itemFormGroup.get('productStock').setValue(value.productStock);
          }
        }
      });

    itemFormGroup.get('price')
      .valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .pipe(
        debounceTime(500),
        distinctUntilChanged()
      )
      .subscribe((value: number) => {
        if (value > 0 && itemFormGroup.get('quantity').value > 0) {
          itemFormGroup.get('amount').setValue(
            this.setAmount(
              value,
              itemFormGroup.get('quantity').value));
        } else {
          itemFormGroup.get('amount').setValue(null);
        }
        this.totalAmount();
      });

    itemFormGroup.get('quantity')
      .valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .pipe(
        debounceTime(500),
        distinctUntilChanged()
      )
      .subscribe((value: number) => {
        if (value > 0 && itemFormGroup.get('price').value > 0) {
          itemFormGroup.get('amount').setValue(
            this.setAmount(
              itemFormGroup.get('price').value,
              value
            ));
        } else {
          itemFormGroup.get('amount').setValue(null);
        }
        this.totalAmount();
      });

    this.currentVatRateCode
      .subscribe(value => {
        if (value) {
          itemFormGroup.get('vatRate.code').setValue(value);
          if (value === 'NONE') {
            itemFormGroup.get('vatRate.code').disable();
          } else {
            itemFormGroup.get('vatRate.code').enable();
          }
        }
      });

    return itemFormGroup;
  }


  setAmount(price, quantity): number {
    return (quantity > 0 && price > 0) ? price * quantity : 0;
  }

  ngOnInit() {
    if (this.authService.user$.value) {
      const currentCookie = $.cookie(`shift-${this.authService.user$.value.user.id}`);
      if (currentCookie) {
        this.currentCashRegister = JSON.parse(currentCookie);
      }
    } else {
      this.authService.userId$.subscribe(
        (userId: number) => {
          const currentCookie = $.cookie(`shift-${userId}`);
          if (currentCookie) {
            this.currentCashRegister = JSON.parse(currentCookie);
            if (this.formGroup && !this.formGroup.get('cashRegister').value) {
              this.formGroup.get('cashRegister').setValue(this.currentCashRegister);
            }
          }
        }
      );
    }
    super.ngOnInit();
  }

  isVatRateCode() {
    return this.defaultVatRateCode === 'NONE';
  }

  isCorrection() {
    if (this.formGroup && this.formGroup.controls.type.value.code && this.formGroup.controls.type.value.code.indexOf('CORRECTION') > -1) {
      this.formGroup.get('correction.baseDate').enable();
      this.formGroup.get('correction.baseNumber').enable();
      this.formGroup.get('correction.description').enable();
      return true;
    } else {
      this.formGroup.get('correction.baseDate').disable();
      this.formGroup.get('correction.baseNumber').disable();
      this.formGroup.get('correction.description').disable();
      return false;
    }
  }

  isDeliveryTypeEmail() {
    if (this.formGroup && this.formGroup.controls.deliveryType.value.code && this.formGroup.controls.deliveryType.value.code === 'EMAIL') {
      this.formGroup.get('customer.email').enable();
      return true;
    } else {
      this.formGroup.get('customer.email').disable();
      return false;
    }
  }

  isDeliveryTypePhone() {
    if (this.formGroup && this.formGroup.controls.deliveryType.value.code && this.formGroup.controls.deliveryType.value.code === 'PHONE') {
      this.formGroup.get('customer.phone').enable();
      return true;
    } else {
      this.formGroup.get('customer.phone').disable();
      return false;
    }
  }

  isProductCode(id) {
    const control = <FormArray>this.formGroup.controls['items'];
    return !!control.controls[id].get('productCode.type.code').value && control.controls[id].get('productCode.type.code').value !== 'NULL';
  }

  countTotalAmount() {
    const control = <FormArray>this.formGroup.controls['items'];
    let amount = 0;
    control.value.map(
      item => {
        if (item.priceWithCharge || item.amount) {
          amount += item.priceWithCharge * item.quantity || item.amount;
        }
      }
    );
    this.currentTotal = amount;
    return amount;
  }

  totalAmount() {
    this.formGroup.controls['total'].setValue(this.countTotalAmount());
  }

  getLabel(id) {
    const control = <FormArray>this.formGroup.controls['items'];
    const productCode = control.controls[id].get('productCode.type.code').value;

    if (productCode === 'MEDICINES') {
      return 'Контрольный (идентификационный) знак (КиЗ)';
    } else if (productCode === 'TOBACCO') {
      return 'Код идентификации';
    } else {
      return 'Серийный номер';
    }

  }

  initIdentifiers() {
    if (this.item && this.item.items && this.item.items.length > 0) {
      const control = new FormArray([]);
      let i;
      for (i in this.item.items) {
        if (this.item.items.hasOwnProperty(i) && this.item.items[i]) {
          control.push(this.getReceiptsBase(
            this.item.items[i].name,
            (this.item.items[i].product ?
              this.item.items[i].product : null),
            this.item.items[i].price,
            this.item.items[i].priceWithCharge,
            this.item.items[i].quantity,
            this.item.items[i].amount,
            this.item.items[i].measure,
            this.item.items[i].paymentObject.code,
            (this.item.items[i].productCode.type && this.item.items[i].productCode.type.code)
              ? this.item.items[i].productCode.type.code : null,
            this.item.items[i].vatRate.code,
            this.item.items[i].productCode.gtin,
            this.item.items[i].productCode.serial,
            this.item.items[i].id,
            this.item.items[i].stock ? this.item.items[i].stock.id : null,
            this.item.items[i].stock ? this.item.items[i].stock.name : null
          ));
        }
      }
      return control;
    }
    return new FormArray([]);
  }

  addReceipt($event?) {
    if ($event) {
      $event.preventDefault();
    }
    const control = <FormArray>this.formGroup.controls['items'];
    control.push(this.getReceiptsBase());
  }

  setRemoveReceipt(id: number) {
    const control = <FormArray>this.formGroup.controls['items'];
    if (!control.controls[id].get('name').value) {
      control.removeAt(id);
    } else {
      this.onDelete(id);
    }
  }

  onDelete(id: number) {
    const control = <FormArray>this.formGroup.controls['items'];
    const dialogRef = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: 'Вы точно хотите удалить позицию?',
        headComment: 'Действие необратимо <br> (' + control.controls[id].get('name').value + ')',
        actions: [
          {
            class: 'btn-st btn-st--left btn-st--gray',
            action: false,
            title: 'Отмена'
          },
          {
            class: 'btn-st btn-st--right btn-st--red',
            action: true,
            title: 'Удалить'
          },
        ],
      }
    });

    dialogRef.afterClosed().subscribe((result: boolean) => {
      if (result) {
        control.removeAt(id);
      }
    });
  }

  onBreakCheck() {

    if (this.formGroup.controls.paymentType.value.code === 'ELECTRONICALLY') {
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
          this.onCashRegisterRegister();
        }
      });
    } else {
      this.onCashRegisterRegister();
    }

  }

  setStock(i, j, value) {
    this.formGroup.get(`items.${i}.stock.id`).setValue(value.productStock[j].stock.id);
    this.formGroup.get(`items.${i}.stock.name`).setValue(value.productStock[j].stock.name);
    this.formGroup.get(`items.${i}.balance`).setValue(value.productStock[j].quantity);
  }

  onCashRegisterRegister() {
    this.loading.next(true);
    this.cashService.onCashRegisterRegister(this.id, (res) => {
      if (res.response.correlationId) {
        this.cashService.getAsyncResult(res.response.correlationId, (data) => {
          this.loading.next(false);
          if (data && data.asyncStatus === 'DONE') {
            this.store.dispatch(new LoadGetAction({
              type: this.type, params: this.item.id,
              onSuccess: (result) => this.item = result.response
            }));
          }
        });
      } else {
        this.loading.next(false);
      }
    });
  }

  onReturn() {
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
        this.cashService.onReturn(this.item.id);
      }
    });
  }

  submit($event?, value?: any): void {
    if ($event) {
      $event.preventDefault();
    }
    if (this.formGroup.valid) {
      const model = value ? value : {...this.formGroup.value};
      const action = this.item.id ? LoadPatchAction : LoadCreateAction;
      if (this.item.id) {
        model.id = this.item.id;
      }

      if (model.cashier && model.cashier.name) {
        delete model.cashier.name;
      }

      if (model.correction && !model.correction.code) {
        delete model.correction;
      }

      model.items.forEach(
        (item, i) => {
          // console.log(item);
          item.vatRate = this.formGroup.get('items')['controls'][i].get('vatRate').value;
          if (!item.gtin) {
            delete item.gtin;
          }
          if (!item.serial) {
            delete item.serial;
          }
          delete item.id;
          if (!item.stock.id) {
            delete item.stock;
            item.stock = null;
          }
          if (item.productStock) {
            delete item.productStock;
          }
        }
      );

      this.store.dispatch(new action({
        type: this.type,
        params: <any>model, onSuccess: (res) => {
          if (res.response.id && !this.idCashReceipt) {
            const n = JSON.parse(JSON.stringify(this.listNavigate));
            n.push(res.response.id);
            this.router.navigate(n).then();
            this.idCashReceipt = res.response.id;
          }
        }
      }));
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
      this.showError = true;
    }
  }

  isReturn() {
    if (this.formGroup.get('type.code').value.indexOf('RETURN') > -1) {
      return false;
    } else if (this.cashService.isDone(this.item)) {
      return true;
    }
    return false;
  }

  onSetAmount() {
    this.setAllAmount();
    this.totalAmount();
  }

  setAllAmount() {
    const control = <FormArray>this.formGroup.controls['items'];
    if (control.value.length > 0) {
      for (const i in control.value) {
        if (control.value.hasOwnProperty(i)) {
          control.controls[i].controls['amount'].setValue(
            this.setAmount(
              control.controls[i].get('price').value,
              control.controls[i].get('quantity').value)
          );
        }
      }
    }
  }

  isCommodity(item: string): boolean {
    return item === 'COMMODITY';
  }

  isEditableItems() {
    return this.editableItems;
  }

  getTitle(id?: any): string {
    let name = '#' + id;
    if (this.item.name) {
      name = this.item.name;
    }
    if (this.cashService.isDone(this.item)) {
      name = name + ' уже распечатан';
    }
    if (this.cashService.isPrinting(this.item)) {
      name = name + ' идет печать';
    }
    return !this.isEdit() ? 'Создать ' + (<string>this.titleName).toLowerCase() : this.titleName + ' ' + name;
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }

  hasProducts() {
    for (const i in this.item.items) {
      if (!this.item.items[i].product) {
        return false;
      }
    }
    return true;
  }

  protected setModel() {
    if (this.item.items && this.item.items.length > 0 && this.hasProducts()) {
      const idArray = [];
      this.item.items.map(item => idArray.push(item.product.id));
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.ReferenceProduct,
        params: {
          filter: {
            id: idArray
          },
          fields: this.productStockFields
        },
        onSuccess: response => {
          this.item.items.map(
            productItem => {
              response.response.items.map(data => {
                if (productItem.product.id === data.id) {
                  if (data.quantity && productItem.stock) {
                    data.productStock.map(
                      stock => {
                        if (stock.stock.id === productItem.stock.id) {
                          productItem.product.balance = stock.quantity;
                        }
                      }
                    );
                  } else {
                    productItem.product.balance = data.quantity;
                  }
                }
              });
            }
          );
          this.setCurrentModel();
        }
      }));
    } else {
      this.setCurrentModel();
    }
    this.loadAppointment();
    this.loadShopOrder();
  }

  protected setCurrentModel() {
    this.formGroup = new FormGroup({
      cashRegister: new FormControl(this.item.cashRegister ?
        {id: this.item.cashRegister.id, name: this.item.cashRegister.name} :
        ((this.currentCashRegister && this.currentCashRegister.id) ? {
          id: this.currentCashRegister.id,
          name: this.currentCashRegister.name
        }
          : null),
        [Validators.required]),
      type: new FormGroup({
        code: new FormControl(this.item.type ? this.item.type.code : 'SELL', [Validators.required])
      }),
      correction: new FormGroup({
        type: new FormGroup({
          code: new FormControl(this.item.correction ? this.item.correction.type.code : null)
        }),
        baseDate: new FormControl(this.item.correction ? this.item.correction.baseDate : null),
        baseNumber: new FormControl(this.item.correction ? this.item.correction.baseNumber : null),
        description: new FormControl(this.item.correction ? this.item.correction.description : null)
      }),
      taxationType: new FormGroup({
        title: new FormControl({value: this.item.taxationType ? this.item.taxationType.title : null, disabled: false})
      }),
      deliveryType: new FormGroup({
        code: new FormControl(this.item.deliveryType ? this.item.deliveryType.code : 'PRINT')
      }),
      customer: new FormGroup({
        email: new FormControl(this.item.customer ? this.item.customer.email : null),
        phone: new FormControl(this.item.customer ? this.item.customer.phone : null)
      }),
      paymentMethod: new FormGroup({
        code: new FormControl(this.item.paymentMethod ? this.item.paymentMethod.code : 'FULL_PAYMENT', [Validators.required])
      }),
      paymentType: new FormGroup({
        code: new FormControl(this.item.paymentType ? this.item.paymentType.code : 'CASH', [Validators.required])
      }),
      total: new FormControl(this.item.total ? this.item.total : null),

      items: this.initIdentifiers()

    });

    if (!this.item || !(this.item && this.item.items) || this.item && this.item.items && this.item.items.length === 0) {
      this.addReceipt();
    }

    this.countTotalAmount();

    this.formGroup.get('cashRegister').valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(
        data => {
          if (data instanceof Object) {
            this.store.dispatch(new LoadGetAction({
              type: CrudType.ReferenceCashRegister,
              params: <any>{
                id: data.id
              },
              onSuccess: (res) => {
                this.formGroup.get('taxationType.title').setValue(res.response.organization.taxationType.title);
                if (res.response.organization.taxationType.code === 'OSN') {
                  this.currentVatRateCode.next('VAT_20');
                  this.defaultVatRateCode = 'VAT_20';
                } else {
                  this.currentVatRateCode.next('NONE');
                  this.defaultVatRateCode = 'NONE';
                }
              }
            }));
          }
        }
      );
  }
  loadAppointment() {
    const id = this.item.id;

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Appointment,
      params: {
        filter: {cashReceipt: {id: id}},
      },
      onSuccess: (res) => {
        if (res.status === true) {
          if (res.response.items.length > 0) {
            this.appointment = res.response.items[0];
          }
          else {
            this.appointment = null;
          }
        }
      }
    }));
  }
  loadShopOrder() {
    const id = this.item.id;

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ShopOrders,
      params: {
        filter: {cashReceipt: {id: id}},
      },
      onSuccess: (res) => {
        if (res.status === true) {
          if (res.response.items.length > 0) {
            this.shopOrder = res.response.items[0];
          }
          else {
            this.shopOrder = null;
          }
        }
      }
    }));
  }
}
