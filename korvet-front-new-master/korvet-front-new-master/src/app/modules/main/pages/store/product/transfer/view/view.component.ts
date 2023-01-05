import {Component, Input, OnInit} from '@angular/core';
import {DocumentProductModel} from '../../../../../../../models/document/document.product.models';
import {CrudType} from '../../../../../../../common/crud-types';
import {DocumentProductTransferModel} from '../../../../../../../models/document/document.product-transfer.models';
import {Observable} from 'rxjs';
import {ReferenceStockModel} from '../../../../../../../models/reference/stock';
import {EnumModel} from '../../../../../../../models/enum .models';
import {FormArray, FormControl, FormGroup, Validators} from '@angular/forms';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({
  selector: 'app-product-transfer-view',
  templateUrl: './view.component.html'
})
export class ViewComponent extends DocumentProductModel implements OnInit {
  crudType = CrudType;
  @Input() referenceStocks: Observable<ReferenceStockModel[]>;
  @Input() DocumentStateEnum: EnumModel[];
  protected listNavigate = ['store', 'product-transfer'];
  protected titleName = 'Перемещение товаров';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService
  ) {
    super(CrudType.ProductTransfer, DocumentProductTransferModel);
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
  }

  public changeStatus(status) {
    this.formGroup.get('state.code').setValue(status);
    this.changeState(CrudType.ProductTransferStatus, CrudType.ProductTransfer);
  }

  protected setModel() {
    const dateTime = this.getRegisterDateTime();
    if (!this.formGroup) {
      this.formGroup = new FormGroup({
        state: new FormGroup({
          code: new FormControl(this.item.state ? this.item.state.code : 'DRAFT', [Validators.required]),
          title: new FormControl(this.item.state ? this.item.state.title : null)
        }, [Validators.required]),
        stockTo: new FormControl(this.item.stockTo, [Validators.required]),
        stockFrom: new FormControl(this.item.stockFrom, [Validators.required]),
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
