import {HttpClient} from '@angular/common/http';
import {Component, OnInit, QueryList, ViewChild, ViewChildren} from '@angular/core';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {Store} from '@ngrx/store';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {Urls} from 'src/app/common/urls';
import {DatepickerOverviewComponent} from 'src/app/modules/shared/components/datepicker-overview/datepicker-overview.component';
import {NotifyService} from 'src/app/services/notify.service';
import {SnackBarService} from 'src/app/services/snack-bar.service';
import {CrudType} from '../../../../../common/crud-types';
import {UiMultiSelectFieldComponent} from '../../../../shared/components/ui-multi-select-field/ui-multi-select-field.component';


@Component(
  {
    templateUrl: './reports-count.component.html'
  })

export class ReportsCountComponent implements OnInit {
  crudType = CrudType;
  showError = false;
  formGroup: FormGroup;
  loader = false;

  @ViewChild('stationsIds') UiMultiSelectFieldBusinessEntity: UiMultiSelectFieldComponent;
  @ViewChild('businessEntityIds') UiMultiSelectFieldUser: UiMultiSelectFieldComponent;

  constructor(
    private http: HttpClient,
    protected store: Store<CrudState>,
    protected notify: SnackBarService,
  ) {
  }

  ngOnInit(): void {
    this.formGroup = new FormGroup({
      stationsIds: new FormControl([]),
      businessEntityIds: new FormControl([]),
    });
  }

  clearForm(): void {
    this.UiMultiSelectFieldUser.onDeselectAll();
    this.UiMultiSelectFieldBusinessEntity.onDeselectAll();
  }

  submit(byType: boolean = false): void {
    if (this.formGroup.valid) {
      this.loader = true;
      const model = {stationsIds: [], businessEntityIds: [], dateFrom: String, dateTo: String};
      if (this.formGroup.controls['stationsIds'].value) {
        for (const item of this.formGroup.controls['stationsIds'].value) {
          model.stationsIds.push(item['id']);
        }
      }
      if (this.formGroup.controls['businessEntityIds'].value) {
        for (const item of this.formGroup.controls['businessEntityIds'].value) {
          model.businessEntityIds.push(item['id']);
        }
      }
      const reportUrl = byType ? Urls.apiReportsCountByTypeReport : Urls.apiReportsCountReport;
      this.http.post(reportUrl, model).subscribe(
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
      this.notify.handleMessage('Заполните обязательные поля', 'warning-snackBar', 2000);
    }
  }
}
