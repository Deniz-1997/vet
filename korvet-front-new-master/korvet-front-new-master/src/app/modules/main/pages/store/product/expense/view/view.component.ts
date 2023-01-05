import {Component, Input, OnInit} from '@angular/core';
import {DocumentProductModel} from '../../../../../../../models/document/document.product.models';
import {CrudType} from '../../../../../../../common/crud-types';
import {Observable} from 'rxjs';
import {ReferenceStockModel} from '../../../../../../../models/reference/stock';
import {EnumModel} from '../../../../../../../models/enum .models';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {DocumentProductExpenseModel} from '../../../../../../../models/document/document.product-expense.models';
import {FormArray, FormControl, FormGroup, Validators} from '@angular/forms';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-product-expense-view',
  templateUrl: './view.component.html'
})
export class ViewComponent extends DocumentProductModel implements OnInit {

  crudType = CrudType;
  @Input() referenceStocks: Observable<ReferenceStockModel[]>;
  @Input() DocumentStateEnum: EnumModel[];
  protected listNavigate = ['store', 'product-expense'];
  protected titleName = 'Расход товаров';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
  ) {
    super(CrudType.ProductExpense, DocumentProductExpenseModel);
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
    this.changeState(CrudType.ProductExpenseStatus, CrudType.ProductExpense);
  }

  protected setModel() {
    const dateTime = this.getRegisterDateTime();
    if (!this.formGroup) {
      this.formGroup = new FormGroup({
        state: new FormGroup({
          code: new FormControl(this.item.state ? this.item.state.code : 'DRAFT', [Validators.required]),
          title: new FormControl(this.item.state ? this.item.state.title : null)
        }, [Validators.required]),
        stock: new FormControl(this.item.stock, [Validators.required]),
        documentProducts: new FormArray([]),
        date: new FormControl(this.item.date ? this.item.date : null),
        time: new FormControl(dateTime.time, [Validators.required]),
        createdAt: new FormControl(this.item.createdAt ? this.item.createdAt : null),
        updatedAt: new FormControl(this.item.updatedAt ? this.item.updatedAt : null),
      });
    } else {
      this.formGroup.patchValue(this.item);
    }
  }

}
