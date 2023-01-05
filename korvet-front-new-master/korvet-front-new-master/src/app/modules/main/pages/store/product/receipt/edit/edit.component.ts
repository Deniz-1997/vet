import {Component, Input, OnInit} from '@angular/core';
import {FormArray, FormControl, FormGroup, Validators} from '@angular/forms';
import {select, Store} from '@ngrx/store';
import {CrudType} from '../../../../../../../common/crud-types';
import {Observable} from 'rxjs';
import {ReferenceStockModel} from '../../../../../../../models/reference/stock';
import {DocumentProductReceiptModel} from '../../../../../../../models/document/document.product-receipt.models';
import {EnumModel} from '../../../../../../../models/enum .models';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {DocumentProductModel} from '../../../../../../../models/document/document.product.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-product-receipt-edit',
  templateUrl: './edit.component.html',
  styleUrls: ['./edit.component.css']
})
export class EditComponent extends DocumentProductModel implements OnInit {

  crudType = CrudType;
  @Input() referenceStocks: Observable<ReferenceStockModel[]>;
  @Input() DocumentStateEnum: EnumModel[];
  protected listNavigate = ['store', 'product-receipt'];
  protected titleName = 'Поступление товаров';
  protected mode = 'edit';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService
  ) {
    super(CrudType.ProductReceipt, DocumentProductReceiptModel);
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

  public changeStatus(event, status) {
    event.preventDefault();
    this.formGroup.get('state.code').setValue(status);
    this.changeState(CrudType.ProductReceiptStatus, CrudType.ProductReceipt);
  }

  protected setModel() {
    const dateTime = this.getRegisterDateTime();
    this.formGroup = new FormGroup({
      state: new FormGroup({
        code: new FormControl(this.item.state ? this.item.state.code : 'DRAFT', [Validators.required]),
        title: new FormControl(this.item.state ? this.item.state.title : null)
      }, [Validators.required]),
      stock: new FormControl(this.item.stock, [Validators.required]),
      documentProducts: new FormArray([]),
      date: new FormControl(dateTime.date),
      time: new FormControl(dateTime.time, [Validators.required]),
      createdAt: new FormControl(this.item.createdAt ? this.item.createdAt : null),
    });
  }
}
