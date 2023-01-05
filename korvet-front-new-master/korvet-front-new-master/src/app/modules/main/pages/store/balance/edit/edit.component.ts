import {Component} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {ProductStockModel} from '../../../../../../models/product/product-stock.models';
import {CrudType} from '../../../../../../common/crud-types';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {Observable} from 'rxjs';
import {ReferenceProductModel} from '../../../../../../models/reference/reference.product.models';
import {ReferenceStockModel} from '../../../../../../models/reference/stock';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-balance-edit',
  templateUrl: './edit.component.html'
})
export class EditComponent extends ReferenceItemModels {
  crudType = CrudType;
  referenceProductItems$: Observable<ReferenceProductModel[]>;
  referenceStockItems$: Observable<ReferenceStockModel[]>;
  protected listNavigate = ['store', 'balance'];
  protected titleName = 'Остаток';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService
  ) {
    super(CrudType.ProductStock, ProductStockModel);

    this.referenceProductItems$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceProduct}));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceProduct,
      params: {
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));

    this.referenceStockItems$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceStock}));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceStock,
      params: {
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      product: new FormControl(this.item.product ? this.item.product : null, [Validators.required]),
      stock: new FormControl(this.item.stock ? this.item.stock : null, [Validators.required]),
      quantity: new FormControl(this.item.quantity ? this.item.quantity : 0, [
        Validators.required,
        Validators.min(0.001),
      ]),
    });
  }

}
