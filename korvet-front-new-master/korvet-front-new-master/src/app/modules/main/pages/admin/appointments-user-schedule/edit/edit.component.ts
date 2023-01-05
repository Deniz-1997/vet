import {Component} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {CrudType} from '../../../../../../common/crud-types';
import {Observable} from 'rxjs';
import {ReferenceCashRegisterModel} from '../../../../../../models/reference/reference.cash.register.models';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {map} from 'rxjs/operators';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {UserScheduleModel} from '../../../../../../models/user/user-schedule.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  crudType = CrudType;
  users$: Observable<{ id: number, fullName: string }[]>;
  public referenceCashRegisterItems: Observable<ReferenceCashRegisterModel[]>;
  protected listNavigate = ['admin', 'appointments', 'user-schedule'];
  protected titleName = 'График работы врачей';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService
  ) {
    super(CrudType.UserSchedule, UserScheduleModel);

    this.users$ = this.store.pipe(select(getCrudModelData, {type: CrudType.User})).pipe(
      map(item => {
        return item.map(user => {
            return {id: user['id'], fullName: user.getFullName()};
          }
        );
      })
    );

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.User,
      params: <any>{
        fields: {0: 'id', 1: 'surname', 2: 'name', 3: 'patronymic'},
        order: {fullName: {middleName: 'ASC'}},
        offset: 0,
        limit: 10
      }
    }));
  }

  getFullName(employee: { name?: string; surname: string; patronymic?: string; }): string {
    return (((employee.surname).trim() + ' ' + (employee.name + ' ' + employee.patronymic).trim()).trim());
  }

  submit($event?): void {
    if ($event) {
      $event.preventDefault();
    }
    const model = {...this.formGroup.value};
    if (this.formGroup.valid) {
      model.dateFrom = this.formGroup.value.dateFrom + ' ' + this.formGroup.value.timeFrom + ':00';
      model.dateTo = this.formGroup.value.dateTo + ' ' + this.formGroup.value.timeTo + ':00';
    }
    super.submit(null, model);
  }

  protected setModel() {
    const dateFrom = (this.item.dateFrom || '').substr(0, 10);
    const timeFrom = (this.item.dateFrom || '').substr(11, 5);
    const dateTo = (this.item.dateTo || '').substr(0, 10);
    const timeTo = (this.item.dateTo || '').substr(11, 5);
    this.formGroup = new FormGroup({
      dateFrom: new FormControl(dateFrom, [Validators.required]),
      timeFrom: new FormControl(timeFrom, [Validators.required]),
      dateTo: new FormControl(dateTo, [Validators.required]),
      timeTo: new FormControl(timeTo, [Validators.required]),
      employee: new FormControl(this.item.employee ?
        {id: this.item.employee.id, fullName: this.getFullName(this.item.employee)} : null, [Validators.required]),
    });
    this.formGroup.controls.dateFrom.valueChanges.subscribe(() => {
      if (!this.formGroup.controls.timeFrom.value) {
        this.formGroup.controls.timeFrom.setValue('00:01');
      }
    });
    this.formGroup.controls.dateTo.valueChanges.subscribe(() => {
      if (!this.formGroup.controls.timeTo.value) {
        this.formGroup.controls.timeTo.setValue('23:59');
      }
    });
  }
}
