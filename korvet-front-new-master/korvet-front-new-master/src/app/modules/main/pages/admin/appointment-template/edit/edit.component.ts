import {Component, Inject, OnDestroy, Optional} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {Observable, Subject} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {CrudType} from '../../../../../../common/crud-types';
import {FormArray, FormControl, FormGroup, Validators} from '@angular/forms';
import {AppointmentTemplateModel} from '../../../../../../models/appointment/appointment-template.models';
import {ReferenceProductModel} from '../../../../../../models/reference/reference.product.models';
import {ModalConfirmComponent} from '../../../../../shared/components/modal-confirm/modal-confirm.component';
import {MAT_DIALOG_DATA, MatDialog, MatDialogRef} from '@angular/material/dialog';
import {ReferenceStockModel} from '../../../../../../models/reference/stock';
import {AuthService} from '../../../../../../services/auth.service';
import {FilterUnitForByUserService} from '../../../../../../services/filter-unit-for-by-user.service';
import {ReferenceUnitModel} from '../../../../../../models/reference/reference.unit.models';
import {GroupModel} from '../../../../../../models/group.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadAppendListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData, getCrudModelCreateLoading} from 'src/app/api/api-connector/crud/crud.selectors';


@Component({templateUrl: './edit.component.html', styleUrls: ['./edit.component.css']})
export class EditComponent extends ReferenceItemModels implements OnDestroy {
  crudType = CrudType;
  referenceStocks$: Observable<ReferenceStockModel[]>;
  referenceStocks: ReferenceStockModel[];
  getLoading$: Observable<boolean>;
  referenceProductItems$: Observable<ReferenceProductModel[]>;
  productFields = {0: 'id', 1: 'name', 2: 'price', 3: 'measure', 4: 'quantity', 5: 'paymentObject'};
  protected listNavigate = ['admin', 'appointment-template'];
  protected titleName = 'Шаблон приема';
  private destroy$ = new Subject<any>();
  filterUnitId: any;
  public unitItems: Observable<ReferenceUnitModel[]>;
  count: number;
  unit;

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    private dialog: MatDialog,
    protected getUnitIdService: FilterUnitForByUserService,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.AppointmentTemplate, AppointmentTemplateModel, data.id, data.openDialog);

    this.referenceStocks$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceStock}));
    this.referenceStocks$.subscribe(item => this.referenceStocks = item);
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceStock,
      params: {
        order: {id: 'ASC'},
        offset: 0,
        limit: 1000
      }
    }));

    this.referenceProductItems$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceProduct}));
    this.getLoading$ = this.store.pipe(select(getCrudModelCreateLoading));
    this.store.dispatch(new LoadGetListAction({type: CrudType.ReferenceProduct, params: {fields: {0: 'id', 1: 'name'}}}));
    this.filterUnitId = getUnitIdService;
    this.unitItems = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceUnit}));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceUnit,
      params: {
        fields: {0: 'id', 1: 'name'},
        order: {surname: 'ASC'},
        offset: 0,
        limit: 20
      },
      onSuccess: res => {
        if (res.response.items.length !== 0 ) {
          this.count = res.response.countItems;
          if (this.count < res.response.totalCount) {
            this.apendList(this.count, CrudType.ReferenceUnit);
          }
        }
      }
    })
    );
    this.unitItems.subscribe(val => {
      this.unit = val;
    });
  }
  apendList(offset, type) {
    this.store.dispatch(new LoadAppendListAction({
      type: type,
      params: {
        order: {surname: 'ASC'},
        fields: {0: 'id', 1: 'name'},
        offset: offset,
        limit: 20
      },
      onSuccess: res => {

        if (res.response.items.length !== 0) {

          offset += res.response.countItems;
          if (offset <= res.response.totalCount) {
            this.apendList(offset, type);
          }
        }

      }
    }));
  }

  initProductItems() {
    if (this.item.products && this.item.products.length > 0) {
      const control = new FormArray([]);
      for (const i in this.item.products) {
        if (this.item.products.hasOwnProperty(i) && this.item.products[i]) {
          const product = this.item.products[i];
          const paymentObject = (product.product.paymentObject ? product.product.paymentObject.code : null);
          const stockId = (paymentObject === 'COMMODITY' ? product.stock.id : null);
          control.push(this.getProductItemBase(
            product.quantity,
            product.product,
            product.id,
            paymentObject,
            stockId,
            product.children,
          ));
        }
      }
      return control;
    }
    return new FormArray([]);
  }

  getProductItemBase(
    quantity: number = 1,
    product?: ReferenceProductModel,
    id?: number,
    paymentObject?: string | null,
    stockId?: number | null,
    items?: any[],
  ) {
    const itemFormGroup = new FormGroup({
      quantity: new FormControl(quantity ? quantity : 1, [
        Validators.required,
        Validators.min(0.001),
      ]),
      product: new FormControl(product ? product : null, [Validators.required]),
      id: new FormControl(id ? id : null),
      paymentObject: new FormControl(paymentObject ? paymentObject : null),
      stock: new FormGroup({
        id: new FormControl(stockId ? stockId : null)
      })
    });
    itemFormGroup.addControl('children', new FormArray((items && items.length) ? items.map(item => {
      const pObject = (item.product.paymentObject ? item.product.paymentObject.code : null);
      const sId = item.stock ? item.stock.id : null;

      return this.getProductItemBase(
        item.quantity,
        item.product,
        item.id,
        pObject,
        sId,
      );
    }) : []));

    return itemFormGroup;
  }

  addProductItem($event?, paymentObject?, stockId?, item?) {
    if ($event) {
      $event.preventDefault();
    }
    const control = item ? item.get('children') as FormArray : <FormArray>this.formGroup.controls['products'];
    control.push(this.getProductItemBase(
      1,
      null,
      null,
      paymentObject,
      stockId));

  }

  removeProductItem(id, $event, item?) {
    if ($event) {
      $event.preventDefault();
    }
    const control = item ? item.get('children') as FormArray : <FormArray>this.formGroup.controls['products'];
    if (!control.controls[id].get('product').value) {
      control.removeAt(id);

    } else {
      this.onDelete(id, item);
    }
  }

  onDelete(id: number, item?: FormControl) {
    const control = item ? item.get('children') as FormArray : <FormArray>this.formGroup.controls['products'];
    const dialogRef = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: 'Вы точно хотите удалить позицию?',
        headComment: 'Действие необратимо <br> (' + control.controls[id].get('product').value.name + ')',
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

  ngOnDestroy(): void {
    this.destroy$.next();
  }

  getStockById(stockId: number) {
    for (const i in this.referenceStocks) {
      if (this.referenceStocks.hasOwnProperty(i) && this.referenceStocks[i].id === stockId) {
        return new ReferenceStockModel(this.referenceStocks[i]);
      }
    }
    return new ReferenceStockModel();
  }

  getFilterProduct(control: FormControl) {
    const value = control.value;
    const filterProduct = {
      active: 1,
      existPrice: 1
    };
    if (value.paymentObject === 'SERVICE') {
      filterProduct['paymentObject'] = value.paymentObject;
    } else if (value.paymentObject === 'COMMODITY') {
      filterProduct['paymentObject'] = value.paymentObject;
      filterProduct['productStock'] = {stock: {id: value.stock.id}};
      filterProduct['existQuantity'] = 1;
    }
    return filterProduct;
  }

  getCountProduct(code: string): number {
    const model = this.formGroup.value;
    let cnt = 0;
    model.products.map(
      item => {
        if (item && item.product && item.product.paymentObject && item.product.paymentObject.code
          && item.product.paymentObject.code === code) {
          cnt++;
        } else if (item && item.paymentObject && item.paymentObject.code
          && item.paymentObject.code === code) {
          cnt++;
        } else if (item && item.paymentObject && typeof item.paymentObject === 'string'
          && item.paymentObject === code) {
          cnt++;
        }
        if (item && item.product && item.children && item.children.length > 0) {
          item.children.map(
            i => {
              if (i.product && i.product.paymentObject && i.product.paymentObject.code && i.product.paymentObject.code === code) {
                cnt++;
              }
            }
          );
        }
      }
    );
    return cnt;
  }

  getPriceAll() {
    const model = this.formGroup.value;
    let price = 0;
    model.products.map(
      item => {
        if (item && item.product && item.product.price) {
          price += item.product.price * item.quantity || 1;
        }
        if (item && item.product && item.children && item.children.length > 0) {
          item.children.map(
            i => {
              if (i.product && i.product.price) {
                price += i.product.price * i.quantity || 1;
              }
            }
          );
        }
      }
    );
    return price;
  }

  submit($event) {
    if ($event) {
      $event.preventDefault();
    }
    if (this.formGroup.valid) {
      const model = this.formGroup.value;
      console.log(model);
      model.products.map(
        item => {
          console.log(item.product.paymentObject);
          item.product.paymentObject = {
            code: item.paymentObject
          };
          delete item.paymentObject;

          if (item.product.children && item.product.children.length > 0) {
            item.product.children.map(
              i => {
                i.product.paymentObject = {
                  code: i.paymentObject
                };
                delete i.paymentObject;
                return i;
              }
            );
          }
          return item;
        }
      );

      super.submit($event, model);

    } else {
      // console.log(getFormValidationErrors(this.formGroup));
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
      this.showError = true;
    }
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      products: this.initProductItems(),
      unit: new FormControl(this.item.unit ? this.item.unit : [], [Validators.required])
    });
  }
}
