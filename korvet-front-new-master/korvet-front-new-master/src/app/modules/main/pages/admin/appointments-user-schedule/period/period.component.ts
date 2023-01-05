import {Component, ViewChild} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {CrudType} from '../../../../../../common/crud-types';
import {Observable} from 'rxjs';
import {ReferenceCashRegisterModel} from '../../../../../../models/reference/reference.cash.register.models';
import {ActivatedRoute, Router} from '@angular/router';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {NotifyService} from '../../../../../../services/notify.service';
import {select, Store} from '@ngrx/store';
import {UserScheduleModel} from '../../../../../../models/user/user-schedule.models';
import {map} from 'rxjs/operators';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {MatCalendar, MatCalendarCellCssClasses} from '@angular/material/datepicker';
import {DatePipe} from '@angular/common';
import {PeriodService} from './period.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadCreateAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './period.component.html', styleUrls: ['./period.component.css']})

export class PeriodComponent extends ReferenceItemModels {
  crudType = CrudType;
  users$: Observable<{ id: number, fullName: string }[]>;
  public referenceCashRegisterItems: Observable<ReferenceCashRegisterModel[]>;
  selectedDate: any;
  minDate: string | null = null;
  datesToHighlight = [];
  @ViewChild('calendar', {static: true}) calendar: MatCalendar<any>;
  protected listNavigate = ['admin', 'appointments', 'user-schedule'];

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    protected datePipe: DatePipe,
    public userSchedulePeriodService: PeriodService,
  ) {
    super(CrudType.UserScheduleCreate, UserScheduleModel);

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

  onSelect(event) {
    this.selectedDate = event;
  }

  onSelect1(calendar) {


    const date = calendar.selected.toJSON();

    this.minDate = date; // any date, only to force rendering

    const index = this.datesToHighlight.indexOf(date);

    if (index !== -1) {
      this.datesToHighlight.splice(index, 1);
    } else {
      this.datesToHighlight.push(date);
    }


    setTimeout(() => {
      this.minDate = null; // Set null to remove the date restriction
    }, 0); // Wait to change-detection function has terminated to execute a new change to force rendering the rows and cells

  }

  dateClass() {
    return (date: Date): MatCalendarCellCssClasses => {
      const highlightDate = this.datesToHighlight
        .map(strDate => new Date(strDate))
        .some(d => d.getDate() === date.getDate() && d.getMonth() === date.getMonth() && d.getFullYear() === date.getFullYear());

      return highlightDate ? 'special-date' : '';
    };
  }

  submit($event?): void {
    if ($event) {
      $event.preventDefault();
    }

    const models = [];
    if (this.formGroup.valid && this.datesToHighlight.length) {

      for (const i in this.datesToHighlight) {
        if (this.datesToHighlight.hasOwnProperty(i)) {
          const dateFrom = new Date(this.datesToHighlight[i]);
          let dateTo = new Date(this.datesToHighlight[i]);

          dateFrom.setHours(this.formGroup.value.timeFrom.split(':')[0], this.formGroup.value.timeFrom.split(':')[1]);

          if (!this.formGroup.value.timeTo) {
            const hour = Math.floor(this.formGroup.value.time);
            const minute = Math.floor((this.formGroup.value.time - hour) * 60);
            dateTo = new Date(dateFrom.getTime() + hour * 3600000 + minute * 60000);
          } else {
            dateTo.setHours(this.formGroup.value.timeTo.split(':')[0], this.formGroup.value.timeTo.split(':')[1]);
            (dateFrom > dateTo) ? dateTo.setDate(dateTo.getDate() + 1) : dateTo.setDate(dateTo.getDate());
          }

          const param = {
            dateFrom: this.datePipe.transform(new Date(dateFrom), 'dd.MM.yyyy HH:mm:ss'),
            dateTo: this.datePipe.transform(new Date(dateTo), 'dd.MM.yyyy HH:mm:ss'),
            employee: {id: this.formGroup.value.employee.id},
          };

          models.push(param);
        }
      }

      if (this.formGroup.valid) {
        const action = LoadCreateAction;

        this.store.dispatch(new action({
          type: this.type,
          params: <any>models, onSuccess: (res) => {
            if (res && res.response.correlationId) {
              this.userSchedulePeriodService.getAsyncResult(res.response.correlationId, (result) => {
                if (result.asyncStatus === 'DONE') {
                  const n = JSON.parse(JSON.stringify(this.listNavigate));
                  this.router.navigate(n).then();
                }
              });
            }
          }
        }));
      } else {
        this.notify.handleMessage('Заполните обязательные поля', 'warning');
        this.showError = true;
      }

    } else if (!this.formGroup.valid) {

      this.notify.handleMessage('Заполните обязательные поля', 'warning');
      this.showError = true;

    } else if (!this.datesToHighlight.length) {

      this.notify.handleMessage('Выберите даты', 'warning');
      this.showError = true;

    }
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      shift: new FormControl(true, [Validators.required]),
      time: new FormControl(12, [Validators.required]),
      dates: new FormControl(12, [Validators.required]),
      timeFrom: new FormControl('', [Validators.required]),
      timeTo: new FormControl(''),
      employee: new FormControl(null, [Validators.required]),
    });
  }
}
