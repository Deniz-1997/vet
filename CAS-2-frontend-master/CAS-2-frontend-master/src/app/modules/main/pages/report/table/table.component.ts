import {Component, EventEmitter, Input, OnInit, ViewChild} from '@angular/core';
import {Router} from '@angular/router';
import {select, Store} from '@ngrx/store';
import {Observable} from 'rxjs';
import {CrudType} from 'src/app/common/crud-types';
import {EnumModel} from 'src/app/models/enum.models';
import {ReferenceBusinessEntityModel} from 'src/app/models/reference/reference.businessEntity.models';
import {ReferenceStationModel} from 'src/app/models/reference/reference.station.models';
import {ReferenceSupervisedObjectModel} from 'src/app/models/reference/reference.supervisedObects.models';
import {DataReportsModel} from 'src/app/models/reports/report-model/date-reports.model';
import {AuthService} from 'src/app/services/auth.service';
import {ReportDataService} from '../report-services/report-data.service';
import {ReportFilterService} from '../report-services/report-filter.service';
import {ReportTypeService} from '../report-services/report-type.service';
import {MatTable} from '@angular/material/table';
import {getCrudModelGetListLoading} from '../../../../../api/api-connector/crud/crud.selectors';
import {LoadAppendListAction, LoadGetListAction} from '../../../../../api/api-connector/crud/crud.actions';
import {CrudState} from '../../../../../api/api-connector/crud/crud-store.service';
import {MatDialog} from '@angular/material/dialog';
import {HistoryReportFormComponent} from '../../../../shared/modules/report-history/history-report-form.component';

class TableData {
  id: number;
  monthNumber: number;
  action: number;
  month: string;
  data: object;
  station: object;
  supervisedObjects: object;
  businessEntity: object;
  status: object;
  buttonDisabled: boolean;
  buttonName: string;
  textColor: string;
  year: number;
  quarter: number;
}

@Component({
  selector: 'app-table',
  templateUrl: 'table.component.html',
  styleUrls: ['../filter/filter.component.scss']
})

export class TableComponent implements OnInit {
  private _station: ReferenceStationModel;
  private _year: number;
  private _month: number;
  private _reportId: number;
  private _businessEntity: ReferenceBusinessEntityModel;
  private _supervisedObject: ReferenceSupervisedObjectModel;
  private _status: EnumModel;
  private _lockUpdate: boolean;
  loading$: Observable<boolean>;


  dataArrayText: string;
  @Input()
  set station(value: ReferenceStationModel) {
    this._station = (value && value['id']) ? value : null;
    if (!this.lockUpdate && this._station === value) {
      this.getReportsList();
    }
  }
  get station(): ReferenceStationModel {
    return this._station;
  }
  @Input()
  set year(value: number) {
    this._year = value;
    if (!this.lockUpdate) {
      this.getReportsList();
    }
  }
  get year(): number {
    return this._year;
  }
  @Input()
  set month(value: number) {
    this._month = value;
    if (!this.lockUpdate) {
      this.getReportsList();
    }
  }
  get month(): number {
    return this._month;
  }
  @Input()
  set reportId(value: number) {
    this._reportId = value;
    if (!this.lockUpdate) {
      this.getReportsList();
    }
  }
  get reportId(): number {
    return this._reportId;
  }
  @Input()
  set businessEntity(value: ReferenceBusinessEntityModel) {
    this._businessEntity = (value && value['id']) ? value : null;
    if (!this.lockUpdate && this._businessEntity === value) {
      this.getReportsList();
    }
  }
  get businessEntity(): ReferenceBusinessEntityModel {
    return this._businessEntity;
  }
  @Input()
  set supervisedObject(value: ReferenceSupervisedObjectModel) {
    this._supervisedObject = (value && value['id']) ? value : null;
    if (!this.lockUpdate && this._supervisedObject === value) {
      this.getReportsList();
    }
  }
  get supervisedObject(): ReferenceSupervisedObjectModel {
    return this._supervisedObject;
  }
  @Input()
  set status(value: EnumModel) {
    this._status = (value && value['id']) ? value : null;
    if (!this.lockUpdate) {
      this.getReportsList();
    }
  }
  get status(): EnumModel {
    return this._status;
  }

  @Input()
  set lockUpdate(value: boolean) {
    if (this._lockUpdate && !value) {
      this.getReportsList();
    }
    this._lockUpdate = value;
  }
  get lockUpdate(): boolean {
    return this._lockUpdate;
  }
  @ViewChild(MatTable) table: MatTable<any>;

  field = [];
  reportStatus: Array<any>;
  months = ReportFilterService.month;
  quarter: number;
  dataArray: Array<TableData> = [];
  userType: string;
  filterData: Object = {};
  offset = 0;
  limit = 20;
  type = CrudType.ReferenceSupervisedObject;
  typeReport = CrudType.ReportData;
  outAppend = new EventEmitter<{limit: number, offset: number}>();
  totalCount = 0;
  fieldsData: {0: string, 1: string, 2: string, 3: string, 4: string,  station?: Array<string>,
    supervisedObjects?: Array<string>, businessEntity?: Array<string>} = {
    0: 'month',
    1: 'statusId',
    2: 'id',
    3: 'year',
    4: 'data',
    station: ['id', 'name'],
    supervisedObjects: ['id', 'name', 'address'],
    businessEntity: ['id', 'name'],
  };

  constructor(private reportData: ReportDataService,
              protected store: Store<CrudState>,
              private reportTypeService: ReportTypeService,
              public dialog: MatDialog,
              private router: Router,
              private authService: AuthService) {
    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: this.typeReport}));
  }


  ngOnInit(): void {
    this.getReportsList();
    this.authService.user$.subscribe(value => {
      if (value !== null) {
        this.userType = value.groups[0].code;
      }
    });
  }

  dataReport(data: any): void {
    this.reportData.setData(data);
    let link = 'create';
    if (!!data.id) {
      link = data.id;
    }
    this.router.navigate([this.router.url, link]);
  }
  openHistory(id: number): void {
    this.dialog.open(HistoryReportFormComponent, {
      height: '100% - 50px',
      width: window.innerWidth > 960 ? '65%' : '90%',
      autoFocus: false,
      data: {
        openDialog: true,
        id: id,
      }
    });
  }

  getReportsList(): void {
    this.dataArray = [];
    this.dataArrayText = null;
    this.field = ['statusId', 'history', 'action'];
    if ((this.station && this.month) || this.supervisedObject) {
      const filters = {year: this.year, reports_id: this.reportId};
      if (this.month) {
        filters['month'] = this.month;
      }
      if (this.supervisedObject) {
        filters['supervisedObjects'] = {'id': this.supervisedObject.id};
      }
      if (this.status) {
        filters['statusId'] = {'id': this.status.id};
      }
      if (this.station && this.month) {
        filters['station'] = {'id': this.station.id};
        this.filterData = filters;
        this.field.unshift('supervisedObjects');
        this.getReportData(this.fieldsData, this.filterData, this.setDataByBE);
      } else {
        if (!this.field.find(n => n === 'month')) {
          this.field.unshift('month');
        }
        this.getReportData(this.fieldsData, filters, this.setDataByMonth);
      }
    }
  }
  appendList(event: {limit: number, offset: number}): void {
    const {limit, offset} = event;
    this.store.dispatch(new LoadAppendListAction({
      type: this.typeReport,
      params: {
        offset: offset + limit,
        limit: limit,
        filter: this.filterData,
        fields: this.fieldsData,
      },
      onSuccess: ({status, response }) => {
        this.offset = offset + limit;
        if (status && response) {
          this.outAppend.emit(event);
          this.setDataByBE(response.items);
        }
      },
    }));
  }

  private getReportData(field: any, filters: any, setData: (n: Array<DataReportsModel>) => void): void {
    this.store.dispatch(new LoadGetListAction({
      type: this.typeReport,
      params: {
        limit: this.limit,
        fields: field,
        filter: filters
      },
      onSuccess: ({status, response }) => {
        if (status && response) {
          const callback = setData.bind(this);
          this.totalCount = response.totalCount;
          callback(response.items as Array<DataReportsModel>);
          if (!response.items.length) {
            this.dataArrayText = 'Данных не найдено';
          }
        }
      }
    }));
  }

  private setDataByMonth(reports: Array<DataReportsModel>): void {
    if (this.month) {
      const month = this.months.find(n => n.monthNumber === this.month);
      this.dataArray.push(this.getDataArray(month.name, month.monthNumber, reports.find(n => n.month === month.monthNumber && n.year === this.year)));
    } else {
      for (const month of this.months) {
        const report = reports.find(n => n.month === month.monthNumber && n.year === this.year);
        if (!this.status) {
          this.dataArray.push(this.getDataArray(month.name, month.monthNumber, report));
        } else {
          if (report && report.statusId.code === this.status.id) {
            this.dataArray.push(this.getDataArray(month.name, month.monthNumber, report));
          }
        }
      }
    }
  }

  private setDataByBE(reports: Array<DataReportsModel>): void {
    for (const item of reports) {
      let tableData = new TableData();
      tableData.monthNumber = this.month;
      tableData.year = this.year;
      tableData.station = this.station;
      tableData.supervisedObjects = item.supervisedObjects;
      tableData.businessEntity = item.businessEntity;
      tableData.id = item.id;
      tableData.status = item.statusId;
      tableData.data = item.data as Object;
      tableData = this.GetTableButtons(tableData);
      if (!this.status || (this.status && tableData.id !== 0)) {
        this.dataArray.push(tableData);
      }
    }
    this.table.renderRows();
  }

  private getDataArray(month: string, monthNumber: number, report: DataReportsModel): TableData {
    let tableData = new TableData();
    tableData.month = month;
    tableData.monthNumber = monthNumber;
    tableData.year = this.year;
    tableData.station = this.station;
    tableData.supervisedObjects = this.supervisedObject;
    tableData.businessEntity = this.businessEntity;
    if (report && report.statusId) {
      tableData.id = report.id;
      tableData.status = report.statusId;
      tableData.data = report.data as Object;
    }
    else {
      tableData.status = {'title': 'Не создан'};
      tableData.id = 0;
      tableData.data = {};
    }
    tableData = this.GetTableButtons(tableData);
    return tableData;
  }

  private GetTableButtons(table: TableData): TableData {
    switch (table.status['code']) {
      case 'sent':
        table.textColor = 'orange';
        if (this.userType === 'ROLE_BUSINESS_ENTITY') {
          table.buttonName = 'Просмотреть';
        } else {
          table.buttonName = 'Открыть';
        }
        break;
      case 'new':
        if (this.userType === 'ROLE_BUSINESS_ENTITY') {
          table.buttonName = 'Редактировать';
        } else {
          table.buttonName = 'Открыть';
          table.buttonDisabled = true;
        }
        break;
      case 'done':
        if (this.userType === 'ROLE_BUSINESS_ENTITY') {
          table.buttonName = 'Просмотреть';
          table.buttonDisabled = true;
        } else {
          table.buttonName = 'Принят';
          table.buttonDisabled = true;
        }
        table.textColor = 'green';
        break;
      case 'returned':
        if (this.userType === 'ROLE_BUSINESS_ENTITY') {
          table.textColor = 'red';
          table.buttonName = 'Редактировать';
        } else {
          table.buttonName = 'Открыть';
          table.buttonDisabled = true;
        }
        break;
      default:
        if (this.userType === 'ROLE_BUSINESS_ENTITY') {
          table.buttonName = 'Создать';
        }
        break;
    }
    return table;
  }
}
