import {ChangeDetectionStrategy, ChangeDetectorRef, Component, OnDestroy, OnInit} from '@angular/core';
import {CrudType} from '../../../../../common/crud-types';
import {combineLatest, Observable, Subject} from 'rxjs';
import {FormControl, FormGroup} from '@angular/forms';
import {ReferenceUnitModel} from '../../../../../models/reference/reference.unit.models';
import {select, Store} from '@ngrx/store';
import {MatDialog} from '@angular/material/dialog';
import {Router} from '@angular/router';
import {DatePipe} from '@angular/common';
import {debounceTime, distinctUntilChanged, map, takeUntil} from 'rxjs/operators';
import moment from 'moment';
import {LeavingModel} from '../../../../../models/leaving/leaving.models';
import {LeavingAddDialogComponent} from '../leaving-add-dialog/leaving-add-dialog.component';
import {UserAuthModel} from 'src/app/api/auth/auth.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {getCrudModelData, getCrudModelGetListLoading} from 'src/app/api/api-connector/crud/crud.selectors';
import {LoadAppendListAction, LoadGetListAction, LoadPatchAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getUser} from 'src/app/api/auth/auth.selectors';

@Component({
  selector: 'app-leaving-schedule',
  templateUrl: './leaving-schedule.component.html',
  styleUrls: ['./leaving-schedule.component.css'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class LeavingScheduleComponent implements OnInit, OnDestroy {

  crudType = CrudType;
  objectKeys = Object.keys;
  selectedDate = new Date();

  type = CrudType.Leaving;
  leaving$: Observable<LeavingModel[]>;

  hours = ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12',
    '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'];
  steps = ['00', '10', '20', '30', '40', '50'];

  public formGroup: FormGroup;

  currentUnit = null;
  public unitItems: Observable<ReferenceUnitModel[]>;
  scheduleDay = {};
  user$: Observable<UserAuthModel>;
  user: UserAuthModel;
  userId: number;
  hour: number;
  step: number;
  date: string;
  count: number;

  loading$: Observable<boolean>;
  loading2$: Observable<boolean>;

  public visibleUnit = true;

  private destroy$ = new Subject<any>();

  constructor(
    protected store: Store<CrudState>,
    public dialog: MatDialog,
    private router: Router,
    private datePipe: DatePipe,
    private cdr: ChangeDetectorRef
  ) {
    this.unitItems = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceUnit}));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceUnit,
      params: {
        fields: {0: 'id', 1: 'name'},
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));

    this.unitItems
      .pipe(
        takeUntil(this.destroy$)
      )
      .pipe(
        debounceTime(500),
        distinctUntilChanged()
      )
      .subscribe(item => {
        if (item.length > 0) {
          if (!this.formGroup) {
            this.currentUnit = item[0];
          }
          if (this.formGroup && !this.formGroup.controls.unit.value) {
            this.formGroup.controls.unit.setValue(item[0]);
          }
        }
      });


    this.loading$ = combineLatest([
      this.store.pipe(select(getCrudModelGetListLoading, {type: CrudType.ReferenceLeavingStatus})),
      this.store.pipe(select(getCrudModelGetListLoading, {type: CrudType.ReferenceUnit})),
      this.store.pipe(select(getCrudModelGetListLoading, {type: CrudType.UserSchedule})),
      this.store.pipe(select(getCrudModelGetListLoading, {type: this.type})),
    ]).pipe(
      map(([item1, item2, item3, item4]) => item1 || item2 || item3 || item4),
    );

    this.loading2$ = this.store.pipe(select(getCrudModelGetListLoading, {type: CrudType.ReferenceUnit}));
  }

  ngOnInit() {
    this.setModel();
    this.user$ = this.store.pipe(select(getUser));
    this.user$.pipe(
      takeUntil(this.destroy$)
    ).pipe(
      debounceTime(500),
      distinctUntilChanged()
    ).subscribe(user => {

      if (user && user.user['unit']) {
        this.currentUnit = {id: user.user['unit'].id, name: user.user['unit'].name};
        this.formGroup.controls.unit.setValue(this.currentUnit);
        this.visibleUnit = false;
      }
    });

    this.leaving$ = this.store.pipe(select(getCrudModelData, {type: this.type}));

    this.formGroup.get('unit').valueChanges.subscribe(
      result => {
        if (result instanceof Object) {
          this.getUsers();
        }
      }
    );

    this.formGroup.get('professions').valueChanges.subscribe(
      result => {
        if (result instanceof Object) {
          this.getUsers();
        }
      }
    );
  }
  apendList(offset, type) {
    this.store.dispatch(new LoadAppendListAction({
      type: type,
      params: {
        order: {surname: 'ASC'},
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

  onSelect(event) {
    this.selectedDate = event;
    this.getUsers();
  }

  getUsers() {
    this.scheduleDay = {};
    if (this.formGroup.get('unit').value) {
      const filter = {
        '>=dateTo': this.datePipe.transform(new Date(this.selectedDate), 'dd.MM.yyyy'),
        '<=dateFrom': this.datePipe.transform(new Date(this.selectedDate), 'dd.MM.yyyy'),
        employee: {
          unit: {
            id: this.formGroup.get('unit').value.id
          }
        }
      };

      if (this.formGroup.get('professions').value) {
        filter.employee['professions'] = {
          id: this.formGroup.get('professions').value.map(v => {
            return v.id;
          })
        };
      }

      this.store.dispatch(new LoadGetListAction({
        type: CrudType.UserSchedule,
        params: {
          filter: filter
        },
        onSuccess: result => {
          if (result.response.items) {
            result.response.items.map(
              user => {
                this.scheduleDay[user.employee.id] = {};
                this.scheduleDay[user.employee.id].dateFrom = user.dateFrom;
                this.scheduleDay[user.employee.id].dateTo = user.dateTo;
                this.scheduleDay[user.employee.id].user = user.employee;
              }
            );
            this.getApp();
          }
        }
      }));
    }
    //
    // this.scrollToTime();
  }

  getApp() {

    for (const key of Object.keys(this.scheduleDay)) {
      this.scheduleDay[key].leaving = {};
    }

    const filter = {
      '>=date': this.datePipe.transform(new Date(this.selectedDate), 'dd.MM.yyyy'),
      '<=date': this.datePipe.transform(new Date(this.selectedDate), 'dd.MM.yyyy'),
      user: {
        unit: {
          id: this.formGroup.get('unit').value.id
        }
      }
    };

    if (this.formGroup.get('professions').value) {
      filter.user['professions'] = {
        id: this.formGroup.get('professions').value.map(v => {
          return v.id;
        })
      };
    }

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Leaving,
      params: <any>{
        filter: filter,
      },
      onSuccess: result => {
        if (result.response.items) {
          result.response.items.map(
            leaving => {
              if (!this.scheduleDay.hasOwnProperty(leaving.user.id)) {
                // console.log('ddd', appointment);
                this.scheduleDay[leaving.user.id] = {};
                this.scheduleDay[leaving.user.id].user = leaving.user;
                if (!this.scheduleDay[leaving.user.id].hasOwnProperty('leaving')) {
                  this.scheduleDay[leaving.user.id].leaving = {};
                }
              }

              const min = Math.round(leaving.date.split(' ')[1].split(':')[1] / 10) * 10;
              const time = leaving.date.split(' ')[1].split(':')[0] + ':' + (min !== 0 ? min : '00');

              this.scheduleDay[leaving.user.id].leaving[time] = {
                date: leaving.date,
                id: leaving.id,
                name: leaving.owner?.name,
                pet: leaving.pet?.name,
                leavingStatus: {
                  color: leaving.leavingStatus.color,
                  name: leaving.leavingStatus.name,
                }
              };
            }
          );

          this.cdr.detectChanges();
          this.scrollToTime();
        }
      }
    }));
  }

  addLeaving(user, time, disabled) {
    if (!disabled) {
      const dialogRef = this.dialog.open(LeavingAddDialogComponent, {
        data: {
          user: user,
          date: this.datePipe.transform(new Date(this.selectedDate), 'dd.MM.yyyy'),
          time: time,
        }
      });

      dialogRef.afterClosed().subscribe(result => {
        if (result) {
          this.getApp();
        }
      });
    }


  }

  time(hour, step) {
    return hour + ':' + step;
  }

  goToView(id, $event?) {
    if ($event) {
      $event.preventDefault();
      $event.stopPropagation();
    }

    this.router.navigate(['/leaving', id]).then();
  }

  hasSchedule(item, hour, step) {
    if (item.dateTo) {
      hour = parseInt(hour);
      step = parseInt(step);
      const today = moment(new Date());
      const dateTo = moment(item.dateTo, 'DD.MM.YYYY hh:mm');
      const dateFrom = moment(item.dateFrom, 'DD.MM.YYYY hh:mm');

      // const currentToday = this.datePipe.transform(new Date(this.selectedDate), 'dd.MM.yyyy').split('.')[0];
      // const currentDayTo = item.dateTo.split(' ')[0].split('.')[0];
      // const currentDayFrom = item.dateFrom.split(' ')[0].split('.')[0];
      //
      // const currentHourTo = item.dateTo.split(' ')[1].split(':')[0];
      // const currentMinuteTo = item.dateTo.split(' ')[1].split(':')[1];
      // const currentHourFrom = item.dateFrom.split(' ')[1].split(':')[0];
      // const currentMinuteFrom = item.dateFrom.split(' ')[1].split(':')[1];


      const year = today.year() >= dateFrom.year() && today.year() <= dateTo.year();
      const hours = hour >= dateFrom.hour() && hour <= dateTo.hour();
      let mins = true;

      if (hour === dateTo.hour()) {
        if (step > dateTo.minutes()) {
          mins = false;
        }
      }

      if (hour === dateFrom.hour()) {
        if (step < dateFrom.minutes()) {
          mins = false;
        }
      }

      return !(year && hours && mins);
    } else {
      return true;
    }
  }

  getUserFullName() {
    return this.formGroup.controls.user.value && this.formGroup.controls.user.value.id ? this.formGroup.controls.user.value.fullName : 'Все';
  }

  getFullName(item: { name?: string; surname: string; patronymic?: string; }): string {
    return (item.surname + ' ' + item.name + ' ' + item.patronymic);
  }

  scrollToTime() {
    const today = new Date();

    if (today.getDate() === this.selectedDate.getDate() && today.getMonth() === this.selectedDate.getMonth()) {
      const min = Math.round(today.getMinutes() / 10) * 10;
      let hours;
      if (min !== 60) {
        hours = today.getHours() < 10 ? '0' + today.getHours() : today.getHours() - 1;
      } else {
        hours = today.getHours() + 1 < 10 ? '0' + today.getHours() + 1 : today.getHours() + 1;
      }
      const time = hours + '-' + (min !== 0 && min !== 60 ? min : '00');
      // const time = document.querySelector('.leaving:not(.disabled)')
      //   .parentElement
      //   .previousElementSibling
      //   .previousElementSibling
      //   .previousElementSibling
      //   .getAttribute('id');
      const hiddenElement = document.getElementById(time);
      console.log(hiddenElement);
      hiddenElement.scrollIntoView({block: 'start', behavior: 'smooth', inline: 'start'});
    }
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }

  onSelectProfessions() {
    this.getUsers();
  }
  drop(item) {
    let leavingDate;
    let id;
    if (item) {
      id = item.id;
      this.date = item.date.split(' ')[0];
      leavingDate = this.date + ' ' + this.hour + ':' + this.step + ':' + '00';
      this.store.dispatch(new LoadPatchAction({
        type: CrudType.Leaving,
        params: {
          id: item.id,
          date: leavingDate,
          user: {id: this.userId}
        }
      }));
      this.getUsers();
    }
  }

  over(item, hour, step) {
    this.hour = hour;
    this.step = step;
    this.userId = item.user.id;
    return this.hour && this.step && this.userId;

  }

  protected setModel() {
    this.formGroup = new FormGroup({
      unit: new FormControl(this.currentUnit),
      user: new FormControl(null),
      professions: new FormControl(null),
    });
    
  }

}
