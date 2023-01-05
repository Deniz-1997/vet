import {
  ChangeDetectorRef,
  Component,
  OnDestroy,
  OnInit, ViewChild,
} from '@angular/core';
import { Router} from '@angular/router';
import {DataReportsModel} from '../../../../../models/reports/report-model/date-reports.model';
import {select, Store} from '@ngrx/store';
import {CrudType} from '../../../../../common/crud-types';
import {FormArray, FormControl, FormGroup} from '@angular/forms';
import {ReportTableComponent, TableArrayModel} from '@korvet/ui-elements';
import {ReportStatusService} from '../report-services/report-status.service';
import {BehaviorSubject, Observable, Subscription} from 'rxjs';
import {SnackBarService} from '../../../../../services/snack-bar.service';
import {AuthService} from '../../../../../services/auth.service';
import {ReportDataService} from '../report-services/report-data.service';
import {ReportModelService} from '../report-services/report-model.service';
import {ReportTypeService} from '../report-services/report-type.service';
import {ReportFilterService} from '../report-services/report-filter.service';
import {ReferenceBusinessEntityModel} from '../../../../../models/reference/reference.businessEntity.models';
import {UserObjectListService} from '../../../../../services/user-object-list.service';
import {ReferenceStationModel} from '../../../../../models/reference/reference.station.models';
import {LoadCreateAction, LoadGetListAction, LoadPatchAction} from '../../../../../api/api-connector/crud/crud.actions';
import {
  getCrudModelCreateLoading,
  getCrudModelCreatePatchLoading
} from '../../../../../api/api-connector/crud/crud.selectors';
import {AuthState} from '../../../../../api/auth/auth.reducer';
import {Params} from '../../../../shared/components/ui-autocomplete/ui-autocomplete.component';
import {ExplanatoryNoteService} from '../report-services/explanatory-note.service';

export class Options {
  value?: string;
  crudType?: string;
}

@Component({
  templateUrl: 'report-form.component.html',
  styleUrls: ['./report-form.component.css']
})

export class ReportFormComponent implements OnInit, OnDestroy {
  @ViewChild(ReportTableComponent) reportTable: ReportTableComponent;
  title: string;
  userType: string;
  type: string;
  report: Array<DataReportsModel>;
  dataReport: Subscription;
  year: number;
  url = [];
  month: string;
  currentStatus: object;
  formGroup: any = new FormGroup({
    month: new FormControl(),
    station: new FormControl(),
    supervisedObjects: new FormControl(),
    businessEntity: new FormControl(),
    year: new FormControl(),
    statusId: new FormControl(),
    data: new FormGroup({}),
    reports: new FormControl(),
    quarter: new FormControl()
  });
  selected = new FormControl(0);
  tabs = [];
  reportStatus: Array<any>;
  loading$ = new BehaviorSubject(false);
  tabActive: string;
  tables: Array<TableArrayModel>;
  reportLoading$: Observable<boolean>;
  isData = false;
  isReadOnly = false;
  filterOption: string;
  valuesAutocomplete = [];
  param: Params;
  fields: any = null;
  limit?: number;
  loading = false;
  readonly explanatoryTabName = 'Пояснительная записка';
  readonly historyTabName = 'История изменений';
  private _id: string;
  get id(): string {
    return this._id;
  }

  set id(value: string) {
    this._id = value;
  }

  private _file: File;
  get file(): File {
    return this._file;
  }
  set file(value: File) {
    this._file = value;
  }
  private _comment: string;
  get comment(): string {
    return this._comment;
  }
  set comment(value: string) {
    this._comment = value;
  }

  get isReportTab(): boolean {
    return this.tabs[this.selected.value] &&
      this.tabs[this.selected.value].name !== this.explanatoryTabName &&
      this.tabs[this.selected.value].name !== this.historyTabName;
  }

  get isExplanatoryTab(): boolean {
    return this.tabs[this.selected.value] &&
      this.tabs[this.selected.value].name === this.explanatoryTabName;
  }

  get isHistoryTab(): boolean {
    return this.tabs[this.selected.value] &&
      this.tabs[this.selected.value].name === this.historyTabName;
  }


  constructor(
    private router: Router,
    private ref: ChangeDetectorRef,
    private store: Store<AuthState>,
    private reportStatusService: ReportStatusService,
    private reportFilterService: ReportFilterService,
    protected reportModelService: ReportModelService,
    private userObjectService: UserObjectListService,
    private reportTypeService: ReportTypeService,
    private snackBar: SnackBarService,
    private reportData: ReportDataService,
    private authService: AuthService,
    private explanatoryNote: ExplanatoryNoteService) {
    this.url = window.location.pathname.split('/');
  }

  ngOnInit(): void {
    this.userObjectService.getCurrentObjectList().subscribe((res: [ReferenceBusinessEntityModel | ReferenceStationModel,
      Array<ReferenceBusinessEntityModel | ReferenceStationModel>, string]) => {
      this.filterOption = res[2];
    });
    this.authService.user$.subscribe(value => {
      if (value !== null) {
        this.userType = value.groups[0].code;
        this.reportStatusService.getStatus().subscribe(val => {
          this.reportStatus = val;
          this.type = this.url[2];
          this.id = this.url[3] === 'create' ? '0' : this.url[3];
          const reportModel = this.reportModelService.getReportModel(this.type);
          this.tabs = reportModel['tabs'];
          this.title = reportModel['title'];
          this.tables = this.tabs[this.selected.value].tables;
          this.tabActive = this.tabs[this.selected.value].group;
          switch (this.id) {
            case '0':
              this.dataReport = this.reportData.currentData.subscribe(v => {
                if ( v !== undefined) {
                  this.reportTypeService.getReport(this.title).subscribe(report => this.formGroup.controls.reports.setValue(report));
                  this.month = v['month'];
                  this.year = v['year'];
                  this.generateForms(v['data']);
                  this.formGroup.controls.month.setValue(v['monthNumber']);
                  this.formGroup.controls.station.setValue(v['station']);
                  this.formGroup.controls.supervisedObjects.setValue(v['supervisedObjects']);
                  this.formGroup.controls.businessEntity.setValue(v['businessEntity']);
                  this.formGroup.controls.year.setValue(v['year']);
                  this.formGroup.controls.statusId.setValue(v['status']);
                  this.formGroup.controls.quarter.setValue(v['quarter']);
                } else {
                  this.router.navigate(['reports', this.type]).then();
                }
              });
              break;
            default:
              this.getReport(this.id);
              break;
          }
        });
      }
    });
  }

  getReport(id: string): void {
    this.loading$.next(true);
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReportData,
      params: {
        filter: {id: Number(id)}
      },
      onSuccess: ({status, response}) => {
        if (status) {
          this.report = response.items[0];
          this.currentStatus = response.items[0]['statusId'];
          this.isReadOnly = Object.values(this.report['statusId']).findIndex(val => val === 'sent' || val === 'done') >= 0;
          this.generateForms(this.report['data'], this.report['statusId']);
          this.month = ReportFilterService.month.find(month => month.monthNumber === this.report['month']).name;
          this.year = this.report['year'];
          this.formGroup.controls.month.setValue(this.report['businessEntity']);
          this.formGroup.controls.month.setValue(this.report['month']);
          this.formGroup.controls.station.setValue(this.report['station']);
          this.formGroup.controls.supervisedObjects.setValue(this.report['supervisedObjects']);
          this.formGroup.controls.businessEntity.setValue(this.report['businessEntity']);
          this.formGroup.controls.year.setValue(this.report['year']);
          this.formGroup.controls.statusId.setValue(this.report['statusId']);
          this.formGroup.controls.reports.setValue(this.report['reports']);
          this.formGroup.controls.quarter.setValue(this.report['quarter']);
          this.loading$.next(false);
        }
      }
    }));

  }

  generateForms(arrayValues: Array<any>,  status?: object): void {
    const sentStatus = this.reportStatus.find(val => val.id === 'sent' || val.id === 'done');
    this.tabs.forEach(tab => {
      if (tab.tables !== undefined) {
        const arrValues = arrayValues[tab.group];
        const forms = [];
        tab.tables.forEach((table: TableArrayModel, index) => {
          if (status !== undefined) {
            switch (sentStatus.name) {
              case status['title']:
                table.columns.forEach(val => val['disabled'] = true);
                break;
              default:
                table.columns.forEach(val => val['disabled'] = false);
                break;
            }
          }
          const valuesCols = typeof arrValues !== 'undefined' ? arrValues[index] ?? [] : [];
          const form = [];
          if (!table.rows.length && valuesCols.length) {
            table.rows = valuesCols;
          }
          table.rows.forEach(cols => {
            const values = valuesCols[cols.position];
            const formCols = table.form();
            Object.keys(cols).forEach(colName => {
              if (formCols.get(colName) !== null) {
                formCols.get(colName).setValue(typeof values !== 'undefined' && typeof values[colName] !== 'undefined' ? values[colName] : cols[colName]);
              }
            });
            form.push(formCols);
          });
          forms.push(new FormArray(form));
        });
        if (this.formGroup.controls['data'].get(tab.group) === null) {
          this.formGroup.controls['data'].addControl(tab.group, new FormArray(forms));
          if (Object.keys(this.formGroup.controls['data'].value).length !== 0) {
            this.isData = true;
          }
        }
      }
    });
  }
  closeReport(): void {
    let optionId: number;
    switch (this.filterOption) {
      case 'supervisedObjects':
        optionId = this.formGroup.controls['supervisedObjects'].value.id;
        break;
      case 'station':
        optionId = this.formGroup.controls['station'].value.id;
        break;
    }
    this.router.navigate(['reports', this.type], {queryParams: {stationId: optionId}}).then();
  }

  filterAutocomplete(option: Options): void {
    const {value, crudType} = option;
    this.param = {};
    this.param.filter = {};
    this.param.offset = 0;
    if (typeof value === 'string' && value.length > 1) {
      this.param.order = {name: 'ASC'};
      this.param.limit = 20;

      if (this.type === CrudType.ReferenceLocation) {
        this.param.filter['~address'] = value;
      } else {
        this.param.filter['~name'] = value;
      }
    } else {
      this.param.order = {sort: 'ASC', name: 'ASC'};
    }

    if (this.fields) {
      this.param.fields = this.fields;
    } else if (crudType === CrudType.ReferenceDisinfectants) {
      this.param.fields = {0: 'id', 1: 'name', 2: 'measurementUnits', 3: 'kind'};
    } else {
      this.param.fields = {0: 'id', 1: 'name'};
    }

    this.loading = true;
    this.store.dispatch(new LoadGetListAction({
      type: crudType,
      params: this.param,
      onSuccess: response => {
        this.loading = false;
        if (response.response.items.length === 0) {
          this.snackBar.handleMessage('Не найдено искомое значение', 'warning-snackBar', 2000);
        }
        this.valuesAutocomplete = response.response.items;
      }
    }));
  }

  submit(statusId: string, isClose: boolean = true): void {
    const model = {...this.formGroup.value};
    const action = this.id !== '0' ? LoadPatchAction : LoadCreateAction;
    if (this.id !== '0') {
      model.id = Number(this.id);
    }
    model.statusId = statusId;
    model.data = this._formatData();
    model.quarter = 1;
    if (this.formGroup.controls['supervisedObjects'].value !== null) {
      model.station = this.formGroup.controls['supervisedObjects'].value.station;
    }


    this.reportLoading$ = this.store.pipe(select(this.id === '0' ? getCrudModelCreatePatchLoading :
      getCrudModelCreateLoading, {type: CrudType.ReportData}));
    this.store.dispatch(new action({
        type: CrudType.ReportData,
        params: model as any,
        fields: {fields: {
            0: 'month',
            1: 'statusId',
            2: 'id',
            3: 'year',
            4: 'data',
            5: 'quarter',
            6: 'reports',
            station: ['id', 'name'],
            supervisedObjects: ['id', 'name'],
            businessEntity: ['id', 'name']
          }
        },
        onSuccess: ({response, status}) => {
          if (status) {
            this.id = String(response.id);
            this.snackBar.handleMessage('Отчет успешно загружен на сервер', 'success-snackBar', 1500);
            if (isClose) {
              if (!!this.file || !!this.comment) {
                this.explanatoryNote.submitExplanatoryNote(this.file, this.comment, response.id);
              }
              this.closeReport();
              return;
            }
          }
        },
        onError: error => {
          const message = error.errors[0].message;
          this.snackBar.handleMessage(message, 'danger-snackBar', 5000);
        }
      }));
    // if (this.formGroup.valid) {
    //
    //   } else {
    //     this.snackBar.handleMessage('Заполните обязательные поля', 'warning-snackBar', 2000);
    //   }
  }

  selectTab($event: any): void {
    if (this.tabs[this.selected.value].tables !== undefined) {
      const data = this._formatData(true);
      Object.keys(data).forEach(key => {
        this.tabs.forEach(tab => {
          if (tab.tables !== undefined) {
            tab.tables.forEach(({isAddRow}) => {
              if (tab.group === key && isAddRow) {
                tab.tables.map(val => val.rows = data[key][0]);
              }
              if (tab.group === key && !isAddRow) {
                tab.tables.map(({rows}) => rows = data[key]);
              }
            });
          }
        });
      });
    }
    this.selected.setValue($event);
    this.tables = this.tabs[this.selected.value].tables;
    this.tabActive = this.tabs[this.selected.value].group;
  }


  private _formatData(changePage: boolean = false): Object {
    const data = {};
    Object.keys(this.formGroup.controls.data.controls).forEach(key => {
      const childControl = this.formGroup.controls.data.get(key);
      data[key] = new Array();
      for (const i in childControl.controls) {
        if (childControl.controls.hasOwnProperty(i)) {
          data[key][i] = new Array();
          for (const j in childControl.controls[i].controls) {
            if (childControl.controls[i].controls.hasOwnProperty(j)) {
              this.tabs.forEach(({tables, group}) => {
                if (tables !== undefined && group === key) {
                  tables.forEach(({isAddRow}) => {
                    if (isAddRow && !changePage) {
                      const lengthObject = Object.keys({...childControl.controls[i].controls[j].value}).length;
                      const countEmpty = this.checkIsEmptyRow({...childControl.controls[i].controls[j].value});
                      if (countEmpty !== lengthObject) {
                        data[key][i].push({...childControl.controls[i].controls[j].value});
                      }
                    } else {
                      data[key][i][j] = {...childControl.controls[i].controls[j].value};
                    }
                  });
                }
              });
            }
          }
        }
      }
    });
    return data;
  }

  checkIsEmptyRow(object: {}): number {
    let count = 0;
    Object.values({...object}).forEach(val => {
      if (val === null || val === '') {
        count++;
      }
    });
    return count;
  }

  ngOnDestroy(): void {
    if (this.dataReport !== undefined) {
      this.dataReport.unsubscribe();
    }
  }
}
