import {Component, Input, OnInit} from '@angular/core';
import {FormArray, FormControl, FormGroup, Validators} from '@angular/forms';
import {Observable} from 'rxjs';
import {ReferenceStockModel} from '../../../../../../../models/reference/stock';
import {CrudType} from '../../../../../../../common/crud-types';
import {ActivatedRoute, Router} from '@angular/router';
import {PetsService} from '../../../../../../../services/pets.service';
import {NotifyService} from '../../../../../../../services/notify.service';
import {select, Store} from '@ngrx/store';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-product-inventory-items',
  templateUrl: './items.component.html',
  styleUrls: ['./items.component.css']
})
export class ItemsComponent implements OnInit {

  @Input() formGroup: FormGroup;
  @Input() documentProducts: [];
  @Input() state: { code: 'DRAFT' };
  @Input() stock: {};
  @Input() referenceStocks: Observable<ReferenceStockModel[]>;
  productStockFields = {0: 'id', 1: 'name', 2: 'quantity', 3: 'productStock'};
  @Input() productStockFilter: { paymentObject: 'COMMODITY' };
  stockProducts: Observable<any>;
  items: FormArray;
  crudType = CrudType;
  protected mode = 'view';


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

  addProduct(event) {
    event.preventDefault();
    const products = this.formGroup.get('documentProducts') as FormArray;
    const item = this.getDefaultItem({});
    item.setValidators([Validators.required]);
    products.push(item);
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
    const documentProducts = new FormArray(this.documentProducts.map(product => this.getDefaultItem(product)), [Validators.required]);
    this.formGroup.setControl('documentProducts', documentProducts);
  }

  changeQuantity(item) {
    const product = item.value['product'];
    const stock = this.formGroup.get('stock').value;
    if (product && stock) {
      product['productStock'].map(productStocks => {
        if (productStocks.stock && productStocks.stock['id'] && productStocks.stock['id'] === stock.id) {
          item.get('quantityActual').setValue(productStocks['quantity']);
        }
      });
    }
  }

  getDefaultItem(item): FormGroup {
    const group = new FormGroup({
      product: new FormControl(item.product ? item.product : {
        name: ''
      }, [Validators.required]),
      quantityAccounting: new FormControl(item.quantityAccounting >= 0 ? item.quantityAccounting : 0),
      quantityActual: new FormControl(item.quantityActual >= 0 ? item.quantityActual : 0),
      quantityDifference: new FormControl(item.quantityAccounting ? item.quantityAccounting - item.quantityActual : 0),
    });
    if (item.id) {
      group.setControl('id', new FormControl(item.id));
    }
    return group;
  }

}
