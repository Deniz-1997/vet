import {HttpClient} from '@angular/common/http';
import {Component, OnInit, QueryList, ViewChild, ViewChildren} from '@angular/core';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {Store} from '@ngrx/store';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {Urls} from 'src/app/common/urls';
import {NotifyService} from 'src/app/services/notify.service';
import {SnackBarService} from 'src/app/services/snack-bar.service';
import {CrudType} from '../../../../../common/crud-types';
import {UiMultiSelectFieldComponent} from '../../../../shared/components/ui-multi-select-field/ui-multi-select-field.component';


@Component(
  {
    templateUrl: './users-business-entity.component.html',
    styleUrls: ['./users-business-entity.component.css']
  })

export class UsersBusinessEntityComponent implements OnInit {
  crudType = CrudType;
  showError = false;
  formGroup: FormGroup;
  loader = false;

  @ViewChild('usersIds') UiMultiSelectFieldBusinessEntity: UiMultiSelectFieldComponent;
  @ViewChild('businessEntityIds') UiMultiSelectFieldUser: UiMultiSelectFieldComponent;

  constructor(
    private http: HttpClient,
    protected store: Store<CrudState>,
    protected notify: SnackBarService,
  ) {
  }

  ngOnInit(): void {
    this.formGroup = new FormGroup({
      usersIds: new FormControl([]),
      businessEntityIds: new FormControl([]),
      excludeSupeuser: new FormControl(true)
    });
  }

  clearForm(): void {
    this.UiMultiSelectFieldUser.onDeselectAll();
    this.UiMultiSelectFieldBusinessEntity.onDeselectAll();
  }

  submit(): void {
    if (this.formGroup.valid) {
      this.loader = true;
      const model = {usersIds: [], businessEntityIds: [], excludeSupeuser: true};
      if (this.formGroup.controls['usersIds'].value) {
        for (const item of this.formGroup.controls['usersIds'].value) {
          model.usersIds.push(item['id']);
        }
      }
      if (this.formGroup.controls['businessEntityIds'].value) {
        for (const item of this.formGroup.controls['businessEntityIds'].value) {
          model.businessEntityIds.push(item['id']);
        }
      }
      if (!this.formGroup.controls['excludeSupeuser'].value) {
        model.excludeSupeuser = false;
      }
      this.http.post(Urls.apiUsersBEReport, model).subscribe(
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
