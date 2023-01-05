import {Component, Input, OnInit} from '@angular/core';
import {AbstractControl, FormArray, FormControl, FormGroup, Validators} from '@angular/forms';
import {Observable} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {CrudType} from '../../../../../common/crud-types';
import {ActivatedRoute, Router} from '@angular/router';
import {PetsService} from '../../../../../services/pets.service';
import {NotifyService} from '../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../services/breadcrumbs.service';
import {ReferenceStockModel} from '../../../../../models/reference/stock';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-product-list',
  templateUrl: './product-list.component.html',
  styleUrls: ['./product-list.component.css']
})
export class ProductListComponent implements OnInit {

  @Input() formGroup: FormGroup;
  @Input() stock: AbstractControl;
  @Input() state: { code: 'DRAFT' };
  @Input() referenceStocks: Observable<ReferenceStockModel[]>;
  productStockFields = {0: 'id', 1: 'name', 2: 'quantity', 3: 'productStock'};
  @Input() productStockFilter: { paymentObject: 'COMMODITY' };
  stockProducts: Observable<any>;
  items: FormArray;
  crudType = CrudType;

  constructor(private router: Router,
              protected route: ActivatedRoute,
              private petsService: PetsService,
              private notify: NotifyService,
              private store: Store<CrudState>,
              protected brdSrv: BreadcrumbsService
  ) {
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceProduct,
      params: {
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));
    this.stockProducts = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceProduct}));
    this.referenceStocks = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceStock}));
  }

  private _documentProducts: [];

  get documentProducts() {
    return this._documentProducts;
  }

  @Input() set documentProducts(v: []) {
    this._documentProducts = v;
    if (this.stock) {
      this.initProducts();
    }
  }

  initProducts() {
    if (this.documentProducts) {
      const documentProducts = new FormArray(this.documentProducts.map(product => this.getDefaultItem(product)));
      this.formGroup.setControl('documentProducts', documentProducts);
    } 
  }

  addProduct(event) {
    event.preventDefault();
    const products = this.formGroup.get('documentProducts') as FormArray;
    products.push(this.getDefaultItem({}));
  }

  removeProduct(event, index) {
    event.preventDefault();
    const products = this.formGroup.get('documentProducts') as FormArray;
    products.removeAt(index);
  }

  ngOnInit(): void {
    this.productStockFilter = this.productStockFilter ? this.productStockFilter : {paymentObject: 'COMMODITY'};
    if (!this.documentProducts) {
      this.documentProducts = [];
    }
    if (!this.state) {
      this.state = {
        code: 'DRAFT'
      };
    }
    this.initProducts();
  }

  getDefaultItem(item): FormGroup {
    const group = new FormGroup({
      product: new FormControl(item.product ? item.product : {
        name: ''
      }, [Validators.required]),
      quantity: new FormControl(item.quantity ? item.quantity : 1, [Validators.required]),
      stockQuantity: new FormControl(this.getStockQuantity(item.product ? item.product : false)),
    });
    if (item.id) {
      group.setControl('id', new FormControl(item.id));
    }
    return group;
  }

  getStockQuantity(product) {
    let stockQuantity = 0;
    if (!product || !product.productStock || !this.stock) {
      return stockQuantity;
    }
    const productStocks = product.productStock;
    productStocks.map(productStock => {
      if (productStock.stock && (productStock.stock.id === this.stock.value['id'])) {
        stockQuantity = productStock.quantity;
      }
    });
    return stockQuantity;
  }

  setStockQuantity(item) {
    item.get('stockQuantity').setValue(0);
    const product = item.get('product');
    if (product && product.value && product.value['productStock']) {
      const stockQuantity = this.getStockQuantity(product.value);
      item.get('stockQuantity').setValue(stockQuantity);
    }
  }
}
