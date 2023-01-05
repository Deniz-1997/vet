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

@Component({
  selector: 'app-culling-report',
  templateUrl: './culling.component.html'
})
export class CullingComponent implements OnInit {
  crudType = CrudType;
  today = this.datePipe.transform(new Date(), 'dd.MM.yyyy HH:mm:ss');
  showError = false;
  loader = false;
  public formGroup: FormGroup;

  constructor(
    private datePipe: DatePipe,
    private http: HttpClient,
    protected store: Store<CrudState>,
    protected notify: NotifyService,
  ) {
  }

  ngOnInit() {
    this.formGroup = new FormGroup({
      startDate: new FormControl(this.today, [Validators.required]),
      endDate: new FormControl(this.today, [Validators.required]),
      contractor: new FormControl(null),
    });
  }

  compareFn(o1: GroupModel, o2: GroupModel): boolean {
    return o1 && o2 ? o1.name === o2.name : o2 === o2;
  }

  submit() {
    if (this.formGroup.valid) {

      this.loader = true;
      const model = this.formGroup.value;

      model.startDate = model.startDate.split(' ')[0] + ' 00:00:00';
      model.endDate = model.endDate.split(' ')[0] + ' 23:59:59';
      if (this.formGroup.controls.contractor.value) {
        model.contractorIds = [this.formGroup.controls.contractor.value["id"]]
      }

      this.http.post<AsyncStatus>(Urls.apiCullingReport, model).subscribe(
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
