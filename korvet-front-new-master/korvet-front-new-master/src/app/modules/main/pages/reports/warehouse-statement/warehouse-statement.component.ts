import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../common/crud-types';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {Observable} from 'rxjs';
import {ReferenceStockModel} from '../../../../../models/reference/stock';
import {DatePipe} from '@angular/common';
import {HttpClient} from '@angular/common/http';
import {select, Store} from '@ngrx/store';
import {GroupModel} from '../../../../../models/group.models';
import {AsyncStatus} from '../../cash/cash.service';
import {Urls} from '../../../../../common/urls';
import {ReferenceProductModel} from '../../../../../models/reference/reference.product.models';
import {NotifyService} from '../../../../../services/notify.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-warehouse-statement',
  templateUrl: './warehouse-statement.component.html'
})
export class WarehouseStatementComponent implements OnInit {
  crudType = CrudType;
  today = this.datePipe.transform(new Date(), 'dd.MM.yyyy HH:mm:ss');
  showError = false;
  loader = false;
  public formGroup: FormGroup;
  public referenceStockItems: Observable<ReferenceStockModel[]>;
  public referenceProductItems: Observable<ReferenceProductModel[]>;

  constructor(
    private datePipe: DatePipe,
    private http: HttpClient,
    protected store: Store<CrudState>,
    protected notify: NotifyService,
  ) {
    this.referenceStockItems = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceStock}));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceStock,
      params: {
        fields: {0: 'id', 1: 'name'},
        order: {name: 'ASC'},
        offset: 0,
        limit: 500
      }
    }));

    this.referenceProductItems = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceProduct}));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceProduct,
      params: {
        fields: {0: 'id', 1: 'name'},
        order: {surname: 'ASC'},
        offset: 0,
        limit: 500
      }
    }));

    this.referenceStockItems.subscribe(
      item => item
    );
  }

  ngOnInit() {
    this.formGroup = new FormGroup({
      startTime: new FormControl(this.today, [Validators.required]),
      endTime: new FormControl(this.today, [Validators.required]),
      stockId: new FormControl(null, [Validators.required]),
      productId: new FormControl(null),
    });
  }

  compareFn(o1: GroupModel, o2: GroupModel): boolean {
    return o1 && o2 ? o1.name === o2.name : o2 === o2;
  }

  submit() {
    if (this.formGroup.valid) {

      this.loader = true;
      const model = this.formGroup.value;

      model.startTime = model.startTime.split(' ')[0] + ' 00:00:00';
      model.endTime = model.endTime.split(' ')[0] + ' 23:59:59';

      this.http.post<AsyncStatus>(Urls.apiWarehouseStatement, model).subscribe(
        item => {
          if (item) {
            const url = item['response'].outputDir + item['response'].name;
            window.open(url);
          }
        },
        () => this.loader = false,
        () => this.loader = false
      );
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
    }
  }
}
