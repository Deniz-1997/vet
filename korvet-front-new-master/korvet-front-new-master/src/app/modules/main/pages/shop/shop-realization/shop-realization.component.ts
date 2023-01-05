import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, ValidatorFn } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { ActivatedRoute, Router } from '@angular/router';
import { Store } from '@ngrx/store';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetAction, LoadGetListAction, LoadPatchAction, LoadCreateAction, LoadDeleteAction} from 'src/app/api/api-connector/crud/crud.actions';
import { CrudType } from 'src/app/common/crud-types';
import { ReferenceProductModel } from 'src/app/models/reference/reference.product.models';
import { ReferenceUnitModel } from 'src/app/models/reference/reference.unit.models';
import { ShopOrderModel } from 'src/app/models/shop/shop.order.models';
import { ModalConfirmComponent } from 'src/app/modules/shared/components/modal-confirm/modal-confirm.component';
import { AuthService } from 'src/app/services/auth.service';
import { NotifyService } from 'src/app/services/notify.service';

@Component({
  selector: 'app-shop-realization',
  templateUrl: './shop-realization.component.html',
  styleUrls: ['./shop-realization.component.css']
})
export class ShopRealizationComponent implements OnInit {
  productsList: Array<ReferenceProductModel> = [];
  formGroup: FormGroup = new FormGroup({
    type: new FormControl(true),
  });
  model: ShopOrderModel = new ShopOrderModel();
  loading: boolean = false;
  PaymentStateTypeEnum = [
    { id: 'NOT_PAID', name: 'Не оплачено' },
    { id: 'PAID', name: 'Оплачено' }
  ];
  stockNotFound: boolean = false;
  constructor(private authService: AuthService, private store: Store<CrudState>,
    protected notify: NotifyService, protected route: ActivatedRoute, protected router: Router,
    private dialog: MatDialog) {
  }

  ngOnInit(): void {
    this.model.id = +this.route.snapshot.paramMap.get('id');
    if (this.model.id) {
      this.loading = true;
      this.store.dispatch(new LoadGetAction({
        type: CrudType.ShopOrders,
        params: this.model.id,
        onSuccess: (res) => {
          if (res.status === true && res.response && res.response.id) {
            this.model = res.response;
            for (const i in this.model.documentProducts) {
              this.addProduct(this.model.documentProducts[i].product, this.model.documentProducts[i].quantity);
            }
            this.formGroup.controls['type'].setValue(this.model.paymentType.code === 'ELECTRONICALLY' ? false : true)
            this.loading = false;
          }
        },
        onError: _ => {
          this.loading = false;
        }
      }));
    }
    else {
      this.loading = true;
      this.authService.user$.subscribe((res) => {
        if (res) {
          this.model.unit = new ReferenceUnitModel();
          this.model.unit.id = res['user']['unit']['id'];
          this.model.user = { id: res['user']['id'], name: res['user']['name'], surname: res['user']['surname'], patronymic: res['user']['patronymic'] };
          if (this.model.unit.id) {
            this.store.dispatch(new LoadGetListAction({
              type: CrudType.ShopSettings,
              params: <any>{
                filter: { unit: { id: this.model.unit.id } },
                offset: 0,
                limit: 10,
              },
              onSuccess: (res) => {
                if (res.status === true && res.response && res.response.items.length && res.response.items[0].data.stock && res.response.items[0].data.stock.id) {
                  this.model.stock = { id: res.response.items[0].data.stock.id, name: res.response.items[0].data.stock.name };
                }
                else {
                  this.stockNotFound = true;
                }
                this.loading = false;
              },
              onError: _ => {
                this.loading = false;
              }
            }));
          }
        }
      })
    }
  }

  addProduct(product, value = 1) {
    if (!product) return;
    if (!this.productsList.find(n => n.id == product['id'])) {
      this.formGroup.addControl('productCount_' + product.id, new FormControl());
      this.formGroup.controls['productCount_' + product.id].setValue(value);
      this.formGroup.controls['productCount_' + product.id].setValidators([this.quantityValidator(this.getProductStockCount(product))]);
      this.productsList.push(product)
    }
    else {
      if (this.formGroup.controls['productCount_' + product.id].value < this.getProductStockCount(product)) {
        this.formGroup.controls['productCount_' + product.id].setValue(++(this.formGroup.controls['productCount_' + product.id].value as number));
      }
    }
  }

  removeProduct(product, i) {
    if (this.productsList.find(n => n.id == product.id) && this.productsList[i] == product) {
      this.formGroup.removeControl('productCount_' + product.id);
      this.productsList.splice(i, 1);
    }
  }

  getSumm(product): number {
    return +this.formGroup.controls['productCount_' + product.id].value * +product.price;
  }

  getAllCount(): number {
    let count = 0;
    for (const i in this.productsList) {
      count += this.formGroup.controls['productCount_' + this.productsList[i].id].value;
    }
    return count;
  }

  getAllAmount(): number {
    let amount = 0;
    for (const i in this.productsList) {
      amount += +this.formGroup.controls['productCount_' + this.productsList[i].id].value * this.productsList[i].price;
    }
    return amount;
  }

  clear() {
    for (const i in this.productsList) {
      this.formGroup.removeControl('productCount_' + this.productsList[i].id);
    }
    this.productsList = [];
  }

  submit() {
    if (this.formGroup.valid) {
      this.loading = true;
      const action = this.model.id ? LoadPatchAction : LoadCreateAction;
      const model = {
        date: new Date().toLocaleString(),
        unit: { id: this.model.unit.id },
        user: { id: this.model.user.id },
        documentProducts: [],
        paymentState: this.PaymentStateTypeEnum['NOT_PAID'],
        state: { code: 'DRAFT' },
        stock: { id: +this.model.stock.id },
        paymentType: { code: this.formGroup.controls['type'].value == false ? 'ELECTRONICALLY' : 'CASH' }
      };
      for (const i in this.productsList) {
        model.documentProducts.push(this.getShopProductItem(this.productsList[i]));
      }
      if (this.model.id) {
        model['id'] = this.model.id;
      }
      this.store.dispatch(new action({
        type: CrudType.ShopOrders,
        params: <any>model,
        onSuccess: (res) => {
          if (res.status === true && res.response && res.response.id) {
            this.model = res.response;
            this.changeOrderState('REGISTERED');
          }
        },
        onError: _ => {
          this.loading = false;
        }
      }));
    } else {
      this.notify.handleMessage('Количество больше остатка на складе', 'danger');
    }
  }

  getShopProductItem(product: ReferenceProductModel) {
    delete product.productStock;
    const shopProduct = {
      product: product,
      user: { id: this.model.user.id },
      stock: { id: +this.model.stock.id },
      price: product.price,
      quantity: this.formGroup.controls['productCount_' + product.id].value,
      amount: +this.formGroup.controls['productCount_' + product.id].value * product.price
    };
    return shopProduct;
  }

  changeOrderState(code: string) {
    this.loading = true;
    this.store.dispatch(new LoadCreateAction({
      type: CrudType.ShopState,
      params: {
        code: code,
        id: this.model.id,
      },
      onSuccess: (res) => {
        if (res.status === true && res.response && res.response.state) {
          this.model.state = res.response.state;
          this.loading = false;
        }
      },
      onError: _ => {
        this.loading = false;
      }
    }));
  }

  createReceipt() {
    this.loading = true;
    this.store.dispatch(new LoadCreateAction({
      type: CrudType.ShopCashReceipt, params: { id: this.model.id },
      onSuccess: (res) => {
        if (res.response && res.response.id) {
          this.router.navigate(['/cash/cash-receipt/', res.response.id]).then();
        }
        this.loading = false;
      },
      onError: _ => {
        this.loading = false;
      }
    }));
  }

  delete() {
    if (this.model.id && !this.model.cashReceipt) {
      const dialogRef = this.dialog.open(ModalConfirmComponent, {
        data: {
          head: 'Вы точно хотите удалить продажу?',
          headComment: 'Действие необратимо',
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
          this.loading = true;
          this.store.dispatch(new LoadDeleteAction({
            type: CrudType.ShopOrders,
            params: {
              id: this.model.id
            },
            onSuccess: _ => {
              this.loading = false;
              this.router.navigate(['/shop/sales-list']).then();
            },
            onError: (error) => {
              this.loading = false;
              this.notify.handleMessage(error.message, 'danger', 5000);
            }
          }));
        }
      });
    }
  }

  quantityValidator(max: number) {
    return (control: FormControl): { [key: string]: boolean } | null => {
      if (!control.value || +control.value > max) {
        control.setErrors({ required: true });
        return { 'quantityValidator': true };
      } else {
        control.setErrors(null);
      }
      return null;
    };
  }

  getProductStockCount(product: ReferenceProductModel) {
    if (product.productStock) {
      let productStock = product.productStock.find(n => n.stock.id == this.model.stock.id);
      return productStock ? productStock.quantity : 0;
    }
  }
  minusProduct(product) {
    const value = this.formGroup.controls['productCount_' + product.id].value - 1;
    value <= 0 ? this.formGroup.controls['productCount_' + product.id].setValue(1) :
      this.formGroup.controls['productCount_' + product.id].setValue(value);


  }
  plusProduct(product) {

    const value = this.formGroup.controls['productCount_' + product.id].value + 1;
    const maxProduct = this.getProductStockCount(product);
    value > maxProduct ? this.formGroup.controls['productCount_' + product.id].setValue(maxProduct) :
      this.formGroup.controls['productCount_' + product.id].setValue(value);
  }
}
