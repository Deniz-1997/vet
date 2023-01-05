import {Component, OnInit} from '@angular/core';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {DatePipe} from '@angular/common';
import {Urls} from '../../../../../common/urls';
import {AsyncStatus} from '../../cash/cash.service';
import {HttpClient} from '@angular/common/http';
import {select, Store} from '@ngrx/store';
import {CrudType} from '../../../../../common/crud-types';
import {GroupModel} from '../../../../../models/group.models';
import {NotifyService} from '../../../../../services/notify.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadAppendListAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({
  templateUrl: './shift.component.html'
})
export class ShiftComponent implements OnInit {
  crudType = CrudType;
  today = this.datePipe.transform(new Date(), 'dd.MM.yyyy HH:mm:ss');
  showError = false;
  loader = false;
  public formGroup: FormGroup;
  count: number;

  constructor(
    private datePipe: DatePipe,
    private http: HttpClient,
    protected store: Store<CrudState>,
    protected notify: NotifyService,
  ) {
  }

  ngOnInit() {
    this.formGroup = new FormGroup({
      stockIds: new FormControl([]),
      date: new FormControl(this.today, [Validators.required]),
    });
  }

  compareFn(o1: GroupModel, o2: GroupModel): boolean {
    return o1 && o2 ? o1.name === o2.name : o2 === o2;
  }
  apendList(offset, type) {
    this.store.dispatch(new LoadAppendListAction({
      type: type,
      params: {
        order: {name: 'ASC'},
        fields: {0: 'id', 1: 'name'},
        offset: offset,
        limit: 20
      },
      onSuccess: res => {

        if (res.response.items.length !== 0) {

          offset += res.response.countItems;
          if (offset <= res.response.totalCount) {
            this.apendList(offset, type);
          }
        }

      }
    }));
  }

  submit() {
    if (this.formGroup.valid) {
      this.loader = true;
      const model = {...this.formGroup.value};
      let stockIds = [];
      for (const i in model.stockIds) {
        stockIds.push(model.stockIds[i]['id']);
      }
      model.stockIds = stockIds;
      this.http.post<AsyncStatus>(Urls.apiShiftReport, model).subscribe(
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
