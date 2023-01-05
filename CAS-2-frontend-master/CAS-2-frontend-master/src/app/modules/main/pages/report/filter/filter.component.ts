import {ChangeDetectorRef, Component, Input, OnDestroy, OnInit, ViewChild} from '@angular/core';
import {Store} from '@ngrx/store';
import {FormControl, FormGroup} from '@angular/forms';
import {ReportFilterService} from '../report-services/report-filter.service';
import {ReferenceSupervisedObjectModel} from '../../../../../models/reference/reference.supervisedObects.models';
import {ReferenceStationModel} from '../../../../../models/reference/reference.station.models';
import {ActivatedRoute, Router} from '@angular/router';
import {ReportTypeService} from '../report-services/report-type.service';
import {ReportModelService} from '../report-services/report-model.service';
import {ReportStatusService} from '../report-services/report-status.service';
import {ReferenceBusinessEntityModel} from '../../../../../models/reference/reference.businessEntity.models';
import {CrudType} from '../../../../../common/crud-types';
import {Subscription} from 'rxjs';
import {UserObjectListService} from '../../../../../services/user-object-list.service';
import {CrudState} from '../../../../../api/api-connector/crud/crud-store.service';
import {debounceTime, distinctUntilChanged} from 'rxjs/operators';
import {MatInput} from '@angular/material/input';


@Component({
  selector: 'app-filter-component',
  templateUrl: './filter.component.html',
  styleUrls: ['filter.component.scss']
})
export class FilterComponent implements OnInit, OnDestroy {
  @ViewChild('search', {read: MatInput}) searchTextBox: MatInput;
  showStation = false;
  showSupervised = false;
  @Input() reportName: string;

  title = 'Поголовье животных';
  filterOption: string;

  objId: number;
  private _reportId: number;
  queryParams: string;

  date = [];
  months = ReportFilterService.month;

  reportStatus: Array<any>;
  supervisedList = new Array<ReferenceSupervisedObjectModel>();

  objectSubscription: Subscription;
  crudType = CrudType;
  stationFilter: any;
  lockTableUpdate = false;
  stationSupervisedFields = {0: 'id', 1: 'name', 2: 'address'};

  formGroup: any = new FormGroup({
    month: new FormControl(),
    station: new FormControl(),
    supervisedObjects: new FormControl(),
    businessEntity: new FormControl(),
    year: new FormControl(),
    statusId: new FormControl(),
    data: new FormGroup({}),
    reports: new FormControl(),
    quarter: new FormControl(),
    reportStatus: new FormControl(),
    stationSupervisedObjects: new FormControl(),
  });

  searchTextBoxControl = new FormControl();
  filterByName = false;
  private _stationList: Array<ReferenceBusinessEntityModel | ReferenceStationModel> = new Array<ReferenceStationModel>();

  get reportId(): any {
    return this._reportId;
  }

  set reportId(value: any) {
    this._reportId = value;
  }

  set stationList(val: any) {
    this._stationList = val;
  }

  get stationList(): any {
    if (this.filterByName) {
      return this._stationList.filter(option => option.name.toLowerCase().includes(
        this.searchTextBoxControl.value.toLowerCase()
      ));
    }
    return this._stationList;
  }

  constructor(
    protected store: Store<CrudState>,
    private router: Router,
    private userObjectService: UserObjectListService,
    private reportTypeService: ReportTypeService,
    private reportModelService: ReportModelService,
    private reportFilterService: ReportFilterService,
    private reportStatusService: ReportStatusService,
    private cdr: ChangeDetectorRef,
    private route: ActivatedRoute) {
    this.searchTextBoxControl.valueChanges.pipe(
      debounceTime(1000),
      distinctUntilChanged()
    ).subscribe(value => {
      this.filterByName = !!value;
    });

  }

  ngOnInit(): void {
    this.openArrayReports(this.reportName);
    this.reportTypeService.reports.subscribe(report => {
      if (!!report) {
        this.reportId = report.id;
        this.queryParams = this.route.snapshot.queryParamMap.get('stationId');
        this.router.navigate(['reports', this.reportName], {queryParams: {stationId: null}}).then();
        if (this.queryParams !== null) {
          setTimeout(() => this.setObjectById(), 300);
        }
      }
    });
    this.formGroup.controls['station'].valueChanges.subscribe((value) => {
      if (value) {
        this.stationFilter = {station: {id: value.id}};
      }
    });
    this.formGroup.controls['stationSupervisedObjects'].valueChanges.subscribe((value) => {
      if (typeof value === 'string' && !value.length) {
        this.formGroup.controls['stationSupervisedObjects'].setValue(null);
      }
    });
  }

  openPanel(): void {
    this.searchTextBox.focus();
  }

  openArrayReports(type: string): void {
    const reportType = this.reportModelService.getReportModel(type);
    if (reportType !== undefined) {
      this.title = reportType['title'];
      this.reportTypeService.getReports(this.title);
      this.reportStatusService.getStatus().subscribe(val => {
        this.reportStatus = val;
      });
      this.objectSubscription = this.userObjectService.getCurrentObjectList().subscribe((res: [ReferenceBusinessEntityModel | ReferenceStationModel,
        Array<ReferenceBusinessEntityModel | ReferenceStationModel>, string]) => {
        this.filterOption = res[2];
        if (this.objId !== undefined) {
          if (this.objId !== res[0].id) {
            this.formGroup.controls[this.filterOption].setValue(null);
            if (this.filterOption === 'station') {
              this.formGroup.controls.stationSupervisedObjects.setValue(null);
            }
          }
        }
        if (res[0] !== null) {
          this.objId = res[0].id;
        }
        switch (this.filterOption) {
          case 'supervisedObjects':
            this.reportFilterService.getSupervisedObjects(res[0]?.id).subscribe(val => {
              this.supervisedList = val;
            });
            this.formGroup.controls['businessEntity'].setValue(res[0]);
            this.showSupervised = true;
            break;
          case 'station':
            this.formGroup.controls['businessEntity'].setValue(null);
            this.stationList = res[1].filter(stations => stations['checked']);
            this.showStation = true;
            break;
        }
        this.reportFilterService.getDate().subscribe(value => this.date = value);

        if (this.formGroup.controls['year'].value === null) {
          this.formGroup.controls['year'].setValue(ReportFilterService.maxDate);
        }

        if (!this.formGroup.controls['month'].value && this.showStation) {
          const currentPeriod = ReportFilterService.month.find(n => n.monthNumber === ReportFilterService.currentMonth);
          this.formGroup.controls['month'].setValue(currentPeriod);
        }
      });
    }
  }

  setObjectById(): void {
      let object: any;
      switch (this.filterOption) {
        case 'supervisedObjects':
          object = this.supervisedList.find(objectList => objectList.id === Number(this.queryParams));
          break;
        case 'station':
          object = this.stationList.find(stationList => stationList.id === Number(this.queryParams));
          break;
      }
      this.formGroup.controls[this.filterOption].setValue(object);
  }

  clearSelect(type: string, $event?: any): void {
    this.formGroup.controls[type].setValue(null);
    $event.stopPropagation();
  }

  getPeriod(): string {
    return this.formGroup.controls['month'].value === null ? '' : 'Факт на ' + this.formGroup.controls['month'].value?.name + ' ' + this.formGroup.controls['year'].value;
  }

  ngOnDestroy(): void {
    this.objectSubscription.unsubscribe();
  }

  clearFilters(): void {
    this.lockTableUpdate = true;
    this.cdr.detectChanges();
    this.formGroup.controls.stationSupervisedObjects.setValue(null);
    this.formGroup.controls.month.setValue(null);
    this.formGroup.controls.supervisedObjects.setValue(null);
    this.formGroup.controls.station.setValue(null);
    this.formGroup.controls.reportStatus.setValue(null);
    this.lockTableUpdate = false;
  }
}
