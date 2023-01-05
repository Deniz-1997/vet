import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../common/crud-types';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {DatePipe} from '@angular/common';
import {HttpClient} from '@angular/common/http';
import {select, Store} from '@ngrx/store';
import {GroupModel} from '../../../../../models/group.models';
import {AsyncStatus} from '../../cash/cash.service';
import {Urls} from '../../../../../common/urls';
import {NotifyService} from '../../../../../services/notify.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({
  selector: 'app-total-revenue-report',
  templateUrl: './total-revenue.component.html'
})
export class TotalRevenueComponent implements OnInit {
  crudType = CrudType;
  today = this.datePipe.transform(new Date(), 'dd.MM.yyyy HH:mm:ss');
  showError = false;
  loader = false;
  formGroup: FormGroup;

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
      products: new FormControl(null),
      unit: new FormControl(null),
      categories: new FormControl(null),
    });
  }

  submit() {
    if (this.formGroup.valid) {

      this.loader = true;
      const model = this.formGroup.value;

      model.startDate = model.startDate.split(' ')[0] + ' 00:00:00';
      model.endDate = model.endDate.split(' ')[0] + ' 23:59:59';
      if (this.formGroup.controls.products.value) {
        model.productsId = new Array<{id: number}>();
        for (const item of this.formGroup.controls.products.value) {
          model.productsId.push(item.id);
        }
        delete model.products;
      }
      if (this.formGroup.controls.unit.value) {
        model.unitId = this.formGroup.controls.unit.value['id'];
      }
      if (this.formGroup.controls.categories.value) {
        model.categoriesId = new Array<{id: number}>();
        for (const item of this.formGroup.controls.categories.value) {
          model.categoriesId.push(item.id);
        }
        delete model.categories;
      }
      this.http.post<AsyncStatus>(Urls.apiTotalRevenueReport, model).subscribe(
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
