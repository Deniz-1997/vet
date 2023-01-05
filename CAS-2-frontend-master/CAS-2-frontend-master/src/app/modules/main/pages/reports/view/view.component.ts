import {ChangeDetectorRef, Component, OnDestroy, OnInit} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {SnackBarService} from '../../../../../services/snack-bar.service';
import {CrudType} from '../../../../../common/crud-types';
import {FormArray, FormControl, FormGroup} from '@angular/forms';
import {ReferenceStationModel} from '../../../../../models/reference/reference.station.models';
import {ReferenceBusinessEntityModel} from '../../../../../models/reference/reference.businessEntity.models';
import {TableArrayModel} from '@korvet/ui-elements';
import {BehaviorSubject, Observable, Subscription} from 'rxjs';
import {ReferenceSupervisedObjectModel} from '../../../../../models/reference/reference.supervisedObects.models';
import {ReportsListModel} from '../../../../../models/reports/report-model/reports-list.model';
import {ReportFilterService} from '../../report/report-services/report-filter.service';
import {ReportStatusService} from '../../report/report-services/report-status.service';
import {ReportModelService} from '../../report/report-services/report-model.service';
import {ReportTypeService} from '../../report/report-services/report-type.service';
import {UserObjectListService} from '../../../../../services/user-object-list.service';
import {
  LoadCreateAction,
  LoadDeleteAction,
  LoadGetListAction
} from '../../../../../api/api-connector/crud/crud.actions';
import {
  getCrudModelCreateLoading,
  getCrudModelCreatePatchLoading,
  getCrudModelGetListLoading, getCrudModelGetLoading
} from '../../../../../api/api-connector/crud/crud.selectors';
import {AuthState} from '../../../../../api/auth/auth.reducer';


interface TableData {
  id: number;
  monthNumber: number;
  action: number;
  month: string;
  station: string;
  supervisedObjects: string;
  status: string;
  data: object;
  buttonDisabled: boolean;
}

@Component({
  templateUrl: './index.component.html',
  styleUrls: ['../../../../shared/modules/report-form/report.component.css']
})
export class ViewComponent implements OnInit, OnDestroy {
  // isSeeReport = true;
  // showStation = false;
  // monthDisabled = false;
  // optionDisabled = false;
  // showSupervised = false;
  //
  // reportLoading$: Observable<boolean>;
  // loading$ = new BehaviorSubject(false);
  //
  // type: string;
  // title: string;
  // hintMonth = '';
  // userGroup: string;
  // hintDevision = '';
  // filterOption: string;
  // controlsElement: string;
  // dataArrayText: string | null;
  // reportCreateStatus: string;
  // reportSentStatus: string;
  //
  // beId: number;
  // actionReport: number;
  // reportDataId: number;
  //
  // date = [];
  // field: Array<string>;
  // months = ReportFilterService.month;
  // civilServantsActionReport = [];
  //
  // reportStatus: Array<any>;
  // dataArray: Array<TableData> = [];
  // civilServantsNoActionReport = [];
  // reports = new Array<ReportsListModel>();
  // stationList = new Array<ReferenceStationModel>();
  // businessEntity = new Array<ReferenceBusinessEntityModel>();
  // supervisedList = new Array<ReferenceSupervisedObjectModel>();
  //
  // private _month_subscriber: Subscription;
  // private paramsSubscription: Subscription;
  // private _filter_subscriber: Subscription;
  // private businessEntitySubscription: Subscription;
  //
  // formGroup: any = new FormGroup({
  //   month: new FormControl(),
  //   station: new FormControl(),
  //   supervisedObjects: new FormControl(),
  //   businessEntity: new FormControl(),
  //   year: new FormControl(),
  //   statusId: new FormControl(),
  //   data: new FormGroup({}),
  //   reports: new FormControl(),
  //   quarter: new FormControl()
  // });
  //
  // constructor(
  //   private store: Store<AuthState>,
  //   private router: Router,
  //   private snackBar: SnackBarService,
  //   private userBEService: UserObjectListService,
  //   private ref: ChangeDetectorRef,
  //   private reportTypeService: ReportTypeService,
  //   private reportModelService: ReportModelService,
  //   private reportFilterService: ReportFilterService,
  //   private reportStatusService: ReportStatusService,
  //   private route: ActivatedRoute) {
  //
  //   this.paramsSubscription = this.route.params.subscribe(params => {
  //     if (params && params['type']) {
  //       this.type = params['type'];
  //       this.reportModelService.getReportModel(this.type).subscribe(val => this.title = val['title']);
  //       this.closeReport();
  //     }
  //   });
  //   this.reportLoading$ = this.store.pipe(select(getCrudModelGetLoading, {type: CrudType.ReportList}));
  // }
  // private _selectedValue: number;
  // private _tabs = [];
  //
  // get tabs(): Array<any> {
  //   return this._tabs;
  // }
  // setTabs(value: any): void {
  //   this._tabs = value;
  // }
  // get selectedValue(): number {
  //   return this._selectedValue;
  // }
  //
  // setSelectedValue(value: number): void {
  //   this._selectedValue = value;
  // }
  //
  // protected preloadData(): void {
  //   this.dataArray = new Array<TableData>();
  //   this.isSeeReport = true;
  //   this.formGroup.controls['data'] = new FormGroup({});
  //   this.stationList = new Array<ReferenceStationModel>();
  //   if (this._month_subscriber) {
  //     this._month_subscriber.unsubscribe();
  //   }
  //   if (this._filter_subscriber) {
  //     this._filter_subscriber.unsubscribe();
  //   }
  //   if (this.businessEntitySubscription) {
  //     this.businessEntitySubscription.unsubscribe();
  //   }
  //   delete this._month_subscriber;
  //   delete this._filter_subscriber;
  //   delete this.businessEntitySubscription;
  //   this.reportStatusService.getStatus().subscribe(val => {
  //     this.reportStatus = val;
  //     this.reportCreateStatus = val.find(({id}) => id === 'new').id;
  //     this.reportSentStatus = val.find(({id}) => id === 'sent').id;
  //   });
  //
  //   this.businessEntitySubscription = this.userBEService.getCurrentObjectList().subscribe((res: [ReferenceBusinessEntityModel | ReferenceStationModel,
  //     Array<ReferenceBusinessEntityModel | ReferenceStationModel>, string]) => {
  //     this.filterOption = res[0] === null ? 'station' : 'supervisedObjects';
  //     if (this.beId !== undefined) {
  //       if (this.beId !== res[0].id) {
  //         this.formGroup.controls['supervisedObjects'].setValue(null);
  //         this.dataArrayText = null;
  //         this.dataArray = [];
  //         this.field = [];
  //       }
  //     }
  //     switch (this.filterOption) {
  //       case 'supervisedObjects':
  //         if (res[0].id !== null) {
  //           this.beId = res[0].id;
  //         }
  //         this.reportFilterService.getSupervisedObjects(res[0]?.id).subscribe(val =>
  //           this.supervisedList = val);
  //         this.formGroup.controls['businessEntity'].setValue(res[0]);
  //         this.showSupervised = true;
  //         break;
  //       case 'station':
  //         this.reportFilterService.getStationList().subscribe(val => {
  //           this.stationList = val;
  //           this.stationList.forEach(station => {
  //             if (this.formGroup.controls['station'].value !== null) {
  //               // tslint:disable-next-line:triple-equals
  //               if (this.formGroup.controls['station'].value.id == station.id) {
  //                 this.formGroup.controls['station'].setValue(station);
  //               }
  //             }
  //           });
  //         });
  //         this.showStation = true;
  //         break;
  //     }
  //
  //     this.reportLoading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: CrudType.ReportData}));
  //
  //     this.reportFilterService.getDate().subscribe(value => this.date = value);
  //
  //     if (this.formGroup.controls['year'].value === null) {
  //       this.formGroup.controls['year'].setValue(ReportFilterService.maxDate);
  //     }
  //
  //     this._month_subscriber = this.formGroup.controls['month'].valueChanges.subscribe(val => this.onSubscribeFilter(val, 'month'));
  //     this._filter_subscriber = this.formGroup.controls[this.filterOption].valueChanges.subscribe(val => this.onSubscribeFilter(val, this.filterOption));
  //
  //     const snapshot = this.route.snapshot.paramMap;
  //
  //     if (snapshot.get('filterType') === this.filterOption) {
  //       this.formGroup.controls[this.filterOption].setValue({id: snapshot.get('id')});
  //     }
  //   });
  // }
  //
  // ngOnInit(): void {
  // }
  //
  // /**
  //  * Обработка филььтров
  //  * @param value
  //  * @param type
  //  */
  // onSubscribeFilter(value: any, type: string): void {
  //   this.controlsElement = type === 'month' ? this.filterOption : 'month';
  //   if (value?.noUpdate === undefined) {
  //     if (this.formGroup.controls[type].value !== null) {
  //       const data = [this.formGroup.controls['year'].value];
  //       let url = `/reports/${this.type}/${type}/`;
  //
  //       if (type === 'month') {
  //         this.hintDevision = 'Выбрана фильтрация по "Отчетному периоду"';
  //         data.push(undefined);
  //         data.push(this.formGroup.controls['month'].value.number);
  //         url += `${this.formGroup.controls['month'].value.number}/`;
  //       } else {
  //         this.hintMonth = 'Выбрана фильтрация по "Подразделению"';
  //         data.push(this.formGroup.controls[this.filterOption].value.id);
  //         url += `${this.formGroup.controls[this.filterOption].value.id}/`;
  //       }
  //       this.reportsList(data[0], data[1], data[2]);
  //     }
  //   }
  // }
  //
  // getErrorMessage(formGroup: FormGroup): string {
  //   if (formGroup.errors !== null) {
  //
  //     if (formGroup.hasError('required')) {
  //       return 'Укажите значение';
  //     }
  //
  //     if (formGroup.hasError('max')) {
  //       return `Максимальное значение ${formGroup.errors.max.max}`;
  //     }
  //
  //     if (formGroup.hasError('min')) {
  //       return `Минимальное значение ${formGroup.errors.max.min}`;
  //     }
  //   }
  // }
  //
  //
  // /**
  //  * Загружаем данные по отчету с сервера
  //  * @param year
  //  * @param option_id
  //  * @param month
  //  */
  // reportsList(year: number, option_id?: number | undefined, month?: string | undefined): void {
  //   this.dataArray = [];
  //   this.field = ['statusId', 'action'];
  //   this.reportTypeService.getReport(this.title).subscribe(report => {
  //
  //     const filters = {year: year, reports_id: report.id};
  //     const isMonth = typeof month !== 'undefined';
  //
  //     const field: {0: string, 1: string, 2: string, 3: string, 4?: string, 5?: string, 6?: string} = {
  //       0: 'month',
  //       1: 'statusId',
  //       2: 'data',
  //       3: 'id',
  //       4: 'station',
  //       5: 'supervisedObjects',
  //       6: 'businessEntity'
  //     };
  //
  //     if (typeof month !== 'undefined' || typeof option_id !== 'undefined') {
  //       this.field.unshift(!isMonth ? 'month' : this.filterOption);
  //       filters[isMonth ? 'month' : this.filterOption] = isMonth ? month : {id: option_id};
  //     }
  //
  //     this.store.dispatch(new LoadGetListAction({
  //       type: CrudType.ReportData,
  //       params: {fields: field, filter: filters},
  //       onSuccess: ({errors, response, status}) => {
  //         if (status) {
  //           const {items, countItems} = response;
  //           if (typeof filters[this.filterOption] !== 'undefined') {
  //             this.dataArray = this.months.map(({name, monthNumber}) => this.getDataArray(name, monthNumber, 'Не создан', '-', '-', ));
  //           } else {
  //             if (!countItems.length) {
  //               this.dataArrayText = 'Данных по месяцу нет';
  //             }
  //           }
  //           items.forEach(item => this.callbackSetDataArray(item, typeof filters[this.filterOption] !== 'undefined' ? this.filterOption : 'month'));
  //         }
  //       }
  //     }));
  //   });
  // }
  //
  // /**
  //  * Заполняем массив dataArray для таблицы table-filer-report
  //  * @param item
  //  * @param type
  //  * @private
  //  */
  // callbackSetDataArray(item: any, type: string): any {
  //   const dataArrayIndex = this.dataArray.findIndex(i => {
  //     switch (this.filterOption) {
  //       case 'station':
  //         return type === 'station' ? i.monthNumber === item.month : i.monthNumber === item.month && i.station === item.station.id;
  //       case 'supervisedObjects':
  //         return type === 'supervisedObjects' ? i.monthNumber === item.month :
  //           i.monthNumber === item.month && i.supervisedObjects === item.supervisedObjects.id;
  //     }
  //   });
  //   if (dataArrayIndex !== -1) {
  //     const tableDatum = this.dataArray[dataArrayIndex];
  //     this.dataArray[dataArrayIndex] = this.getDataArray(
  //       tableDatum.month, tableDatum.monthNumber,
  //       item.statusId.title, item.station.name, item.supervisedObjects?.name, 1, item.data, item.id);
  //   } else {
  //     this.dataArray.push(this.getDataArray(
  //       this.months.find(m => m.monthNumber === item.month).name, item.month,
  //       item.statusId.title, item.station.name, item.supervisedObjects?.name, 1, item.data, item.id));
  //   }
  // }
  //
  // /**
  //  * Конвертируем данные для table-filer-report
  //  * @param month
  //  * @param monthNumber
  //  * @param status
  //  * @param action
  //  * @param station
  //  * @param supervisedObjects
  //  * @param data
  //  * @param id
  //  * @param buttonDisabled
  //  */
  // getDataArray(month: string, monthNumber: number, status: string, station: string, supervisedObjects: string, action: number = 0, data: object = {},
  //              id: number = 0, buttonDisabled: boolean = false): TableData {
  //   const tableData: TableData = {
  //     id: id, status: status, month: month, monthNumber: monthNumber, station: station, supervisedObjects: supervisedObjects,
  //     data: data, action: action, buttonDisabled: buttonDisabled
  //   };
  //   this.reportStatus.find(val => {
  //     if (val.name === status) {
  //       switch (val.id) {
  //         case 'sent':
  //           tableData.buttonDisabled = true;
  //           break;
  //       }
  //     }
  //   });
  //
  //
  //   return tableData;
  // }
  //
  // closeReport(): void {
  //   this.isSeeReport = true;
  //   this.reportDataId = undefined;
  //   this.actionReport = undefined;
  //
  //   const isMonth = this.controlsElement === 'month';
  //
  //   if (isMonth) {
  //     this.hintMonth = '';
  //   } else {
  //     this.hintDevision = '';
  //   }
  //
  //   this.formGroup.controls[isMonth ? this.filterOption : 'month'].setValue(null);
  //   this.preloadData();
  // }
  //
  // /**
  //  * Открываем отчет по типу
  //  * @param element
  //  * @param changeUrl
  //  */
  // openReport(element: Array<TableData>, changeUrl: boolean = true): void {
  //   this.loading$.next(true);
  //   this.ref.detectChanges();
  //   this.reportDataId = element['id'];
  //   this.actionReport = element['action'];
  //   this.formGroup.get('month').setValue({...this.months.find(v => v.monthNumber === element['monthNumber']), noUpdate: true});
  //   this.isSeeReport = false;
  //   this.civilServantsActionReport.push(element['data']);
  //   this.generateForms(element['data']);
  //   this.loading$.next(false);
  // }
  //
  // /**
  //  * Генерируем форму отчета
  //  * @param arrayValues
  //  */
  // generateForms(arrayValues: Array<any>): void {
  //   this.tabs.forEach(tab => {
  //     if (tab.tables !== undefined) {
  //       const arrValues = arrayValues[tab.group];
  //       const forms = [];
  //       tab.tables.forEach((table: TableArrayModel, index) => {
  //         const valuesCols = typeof arrValues !== 'undefined' ? arrValues[index] ?? [] : [];
  //         const form = [];
  //         if (!table.rows.length && valuesCols.length) {
  //           table.rows = valuesCols;
  //         }
  //         table.rows.forEach(cols => {
  //           const values = valuesCols[cols.position];
  //           const formCols = table.form();
  //           Object.keys(cols).forEach(colName => {
  //             if (formCols.get(colName) !== null) {
  //               formCols.get(colName).setValue(typeof values !== 'undefined' && typeof values[colName] !== 'undefined' ? values[colName] : cols[colName]);
  //             }
  //           });
  //           form.push(formCols);
  //         });
  //         forms.push(new FormArray(form));
  //       });
  //       if (this.formGroup.controls['data'].get(tab.group) === null) {
  //         this.formGroup.controls['data'].addControl(tab.group, new FormArray(forms));
  //       }
  //     }
  //   });
  // }
  //
  // /**
  //  * Сохраяем массив данных по отчету
  //  * @param statusId
  //  * @param $event
  //  * @param value
  //  */
  // submit(statusId: string, $event?: any, value?: any): void {
  //   if ($event) {
  //     $event.preventDefault();
  //   }
  //
  //   const model = value ? value : {...this.formGroup.value};
  //   model.data = this._formatData();
  //   // const action = this.actionReport ? LoadPatchAction : LoadCreateAction;
  //   const action = LoadCreateAction;
  //   if (this.filterOption === 'supervisedObjects') {
  //     model.station = this.formGroup.controls['supervisedObjects'].value.station;
  //   }
  //   if (this.actionReport) {
  //     model.id = this.reportDataId;
  //     this.store.dispatch(new LoadDeleteAction({
  //       type: CrudType.ReportData,
  //       params: {
  //         id: model.id
  //       }
  //     }));
  //     delete model.id;
  //   }
  //   // console.log(statusId);
  //   model.quarter = 1;
  //   model.statusId = statusId;
  //   model.month = model.month.monthNumber;
  //   this.reportTypeService.getReport(this.title).subscribe(report => {
  //     model.reports = report;
  //     const selVal = this.selectedValue !== undefined ? this.selectedValue : 0;
  //     if (this.tabs[selVal].name === 'Отчет') {
  //       if (this.userGroup === 'ROLE_GOVERNMENT') {
  //         model.data.main.forEach((val, idx) => {
  //           this.actionReport ? val.unshift(this.civilServantsActionReport[0].main[idx][0]) : val.unshift(this.civilServantsNoActionReport[idx]);
  //         });
  //       } else if (this.userGroup === 'ROLE_BUSINESS_ENTITY') {
  //         model.data.main.forEach((val, idx) => {
  //           this.actionReport ? val.push(this.civilServantsActionReport[0].main[idx][1]) : val.push(this.civilServantsNoActionReport[idx]);
  //         });
  //       }
  //     }
  //
  //
  //     this.reportLoading$ = this.store.pipe(select(this.actionReport ? getCrudModelCreatePatchLoading :
  //       getCrudModelCreateLoading, {type: CrudType.ReportData}));
  //
  //     this.store.dispatch(new action({
  //       type: CrudType.ReportData,
  //       params: model as any,
  //       onSuccess: ({errors, response, status}) => {
  //         if (status) {
  //           this.snackBar.handleMessage('Отчет успешно загружен на сервер', 'success-snackBar', 2000);
  //           switch (this.filterOption) {
  //             case 'supervisedObjects':
  //               model.supervisedObjects.id !== undefined ? this.reportsList(model.year, model.supervisedObjects.id) :
  //                 this.reportsList(model.year, undefined, model.month);
  //               break;
  //             case 'station':
  //               model.station.id !== undefined ? this.reportsList(model.year, model.station.id) : this.reportsList(model.year, undefined, model.month);
  //               break;
  //           }
  //           this.closeReport();
  //           return;
  //         }
  //
  //       },
  //       onError: error => {
  //         const message = error.errors[0].message;
  //         this.snackBar.handleMessage(message, 'danger-snackBar', 5000);
  //       }
  //     }));
  //     if (this.formGroup.valid) {
  //
  //     } else {
  //       // this.snackBar.handleMessage('Заполните обязательные поля', 'warning-snackBar', 2000);
  //     }
  //   });
  // }
  //
  // private _formatData(): Object {
  //   const data = {};
  //   Object.keys(this.formGroup.controls.data.controls).forEach(key => {
  //     const childControl = this.formGroup.controls.data.get(key);
  //     data[key] = new Array();
  //     for (const i in childControl.controls) {
  //       if (childControl.controls.hasOwnProperty(i)) {
  //         data[key][i] = new Array();
  //         for (const j in childControl.controls[i].controls) {
  //           if (childControl.controls[i].controls.hasOwnProperty(j)) {
  //             data[key][i][j] = {...childControl.controls[i].controls[j].value};
  //           }
  //         }
  //       }
  //     }
  //   });
  //   return data;
  // }
  //
  // /**
  //  *
  //  * @param type
  //  * @param $event
  //  */
  // clearSelect(type: string, $event: any): void {
  //   this.formGroup.controls[type].setValue(null);
  //   this.dataArrayText = null;
  //   this.dataArray = [];
  //   this.field = [];
  //
  //   if (type === 'month') {
  //     this.hintDevision = '';
  //     this.optionDisabled = false;
  //   } else {
  //     this.hintMonth = '';
  //     this.monthDisabled = false;
  //   }
  //
  //   $event.stopPropagation();
  // }
  //
  // getErrors(tabActive: any, tableIndex: any, table: any): object {
  //   const errors = {};
  //
  //   this.formGroup.get(['data', tabActive, tableIndex]).controls.map(controlsTable => {
  //     Object.keys(controlsTable.controls).map(key => {
  //       const control = controlsTable.controls[key];
  //       if (control.errors !== null) {
  //         Object.keys(control.errors).forEach(nameError => {
  //           if (typeof errors[nameError] === 'undefined') {
  //             errors[nameError] = [];
  //           }
  //         });
  //
  //         // errors.push(this.getErrorMessage(control));
  //       }
  //     });
  //   });
  //
  //   return Object.keys(errors);
  // }
  //
  // getPeriod(): string {
  //   return this.formGroup.controls['month'].value === null ? '' : 'Факт на ' + this.formGroup.controls['month'].value?.name + ' ' + this.formGroup.controls['year'].value;
  // }
  //
  // ngOnDestroy(): void {
  //   this.businessEntitySubscription.unsubscribe();
  //   this.paramsSubscription.unsubscribe();
  //   this._month_subscriber.unsubscribe();
  //   this._filter_subscriber.unsubscribe();
  // }
}
