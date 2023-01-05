import {Component, Input, OnInit} from '@angular/core';
import {ReferenceProductModel} from '../../../../models/reference/reference.product.models';
import {combineLatest, Observable} from 'rxjs';
import {ProductToServiceModel} from '../../../../models/product/product-to-service.models';
import {select, Store} from '@ngrx/store';
import {CrudType} from '../../../../common/crud-types';
import {FormControl} from '@angular/forms';
import {map} from 'rxjs/operators';
import {ApiParamsInterface} from 'src/app/api/api-connector/api-connector.models';
import {LoadCreateAction, LoadDeleteAction, LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {getCrudModelCreateLoading, getCrudModelData, getCrudModelDeleteLoading, getCrudModelGetListLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-product-to-services-form',
  templateUrl: './product-to-services-form.component.html',
  styleUrls: ['./product-to-services-form.component.css']
})
export class ProductToServicesFormComponent implements OnInit {

  @Input() product: ReferenceProductModel;
  @Input() productToServices$: Observable<ProductToServiceModel[]>;

  autocompleteType = CrudType.ReferenceProduct;
  autocompleteBaseParams: ApiParamsInterface = {};
  control: FormControl;
  loading$: Observable<boolean>;
  addMode = false;

  constructor(
    private store: Store<CrudState>,
  ) {
  }

  ngOnInit() {
    if (!this.productToServices$) {
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.ProductToService,
        params: {
          filter: {paymentProduct: {id: this.product.id}}
        }
      }));
      this.productToServices$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ProductToService}));
    }
    this.loading$ = combineLatest([
      this.store.pipe(select(getCrudModelGetListLoading, {type: CrudType.ProductToService})),
      this.store.pipe(select(getCrudModelDeleteLoading, {type: CrudType.ProductToService})),
      this.store.pipe(select(getCrudModelCreateLoading, {type: CrudType.ProductToService})),
    ]).pipe(map(loading => loading.some(l => l)));
    this.control = new FormControl();
  }

  onSelected(item: ReferenceProductModel): void {
    if (item) {
      this.store.dispatch(new LoadCreateAction({
        type: CrudType.ProductToService,
        params: {
          paymentProduct: {id: this.product.id},
          paymentService: {id: item.id},
        },
        onSuccess: () => this.add(),
      }));
    }
  }

  remove(item: ProductToServiceModel): void {
    if (item) {
      this.store.dispatch(new LoadDeleteAction({
        type: CrudType.ProductToService,
        params: {id: item.id},
      }));
    }
  }

  add(): void {
    this.addMode = !this.addMode;
  }
}
