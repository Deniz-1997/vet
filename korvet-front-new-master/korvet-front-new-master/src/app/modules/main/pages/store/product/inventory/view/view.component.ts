import {Component, Input, OnInit} from '@angular/core';
import {DocumentProductModel} from '../../../../../../../models/document/document.product.models';
import {CrudType} from '../../../../../../../common/crud-types';
import {DocumentProductInventoryModel} from '../../../../../../../models/document/document.product-inventory.models';
import {Observable} from 'rxjs';
import {ReferenceStockModel} from '../../../../../../../models/reference/stock';
import {EnumModel} from '../../../../../../../models/enum .models';
import {select, Store} from '@ngrx/store';
import {FormArray, FormControl, FormGroup, Validators} from '@angular/forms';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-product-inventory-view',
  templateUrl: './view.component.html'
})
export class ViewComponent extends DocumentProductModel implements OnInit {
  crudType = CrudType;
  @Input() referenceStocks: Observable<ReferenceStockModel[]>;
  @Input() DocumentStateEnum: EnumModel[];
  protected listNavigate = ['store', 'product-inventory'];
  protected titleName = 'Инвентаризация';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService
  ) {
    super(CrudType.ProductInventory, DocumentProductInventoryModel);
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Enum,
      params: {
        filter: {
          id: [
            'DocumentStateEnum'
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
    this.referenceStocks = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceStock}));
  }

  public changeStatus(status) {
    this.formGroup.get('state.code').setValue(status);
    this.changeState(CrudType.ProductInventoryStatus, CrudType.ProductInventory);
  }

  protected setModel() {
    const dateTime = this.getRegisterDateTime();
    if (!this.formGroup) {
      this.formGroup = new FormGroup({
        state: new FormGroup({
          code: new FormControl(this.item.state ? this.item.state.code : 'DRAFT'),
          title: new FormControl(this.item.state ? this.item.state.title : null)
        }, [Validators.required]),
        stock: new FormControl(this.item.stock),
        documentProducts: new FormArray([]),
        date: new FormControl(dateTime.date),
        time: new FormControl(dateTime.time),
        createdAt: new FormControl(this.item.createdAt ? this.item.createdAt : null),
      });
    } else {
      this.formGroup.patchValue(this.item);
    }
  }
}
