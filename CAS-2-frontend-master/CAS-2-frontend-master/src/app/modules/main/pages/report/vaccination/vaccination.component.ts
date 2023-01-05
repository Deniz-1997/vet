import {Component, OnInit} from '@angular/core';
import {MatDialog} from '@angular/material/dialog';
import {select, Store} from '@ngrx/store';
import {Observable} from 'rxjs';
import {CrudType} from 'src/app/common/crud-types';
import {PersonModel} from 'src/app/models/auth/person.model';
import {VaccineSeriesModel} from 'src/app/models/dictionary/vaccine-series.model';
import {UploadedVaccinationExcelFileEntryModel} from 'src/app/models/Import/uploaded-vaccination-excel-file-entry.model';
import {getCrudModelGetListLoading} from '../../../../../api/api-connector/crud/crud.selectors';
import {CrudState} from '../../../../../api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from '../../../../../api/api-connector/crud/crud.actions';
import {ReferenceBusinessEntityModel} from 'src/app/models/reference/reference.businessEntity.models';
import {ReferenceStationModel} from 'src/app/models/reference/reference.station.models';
import {UserObjectListService} from 'src/app/services/user-object-list.service';
import {UsersService} from 'src/app/services/users.service';
import {ApiQueueRowModel} from 'src/app/models/vaccination/api-queue-row.model';
import {ModalVaccinationImportComponent} from './modal-vaccination-import/modal-import.component';
import {AuthService} from 'src/app/services/auth.service';

@Component({
  selector: 'app-vaccination',
  templateUrl: './vaccination.component.html',
  styleUrls: ['./vaccination.component.scss']
})
export class VaccinationComponent implements OnInit {
  public loading$: Observable<boolean>;
  type = CrudType.ApiQueue;
  offset = 0;
  limit = 50;
  fields = {
    0: 'id',
    1: 'createdAt',
    2: 'name',
    rows: ['status'],
    'businessEntity': {0: 'id', 1: 'name'},
    'station': {0: 'id', 1: 'name'},
    'user': {0: 'id', 1: 'name', 2: 'surname', 3: 'patronymic'}
  };
  order: {updatedAt: 'DESC'};
  displayedColumns = ['date', 'name', 'station', 'status', 'user'];
  isEmptyInformation = 'Еще нет загруженных файлов. ';
  public excelFiles: Array<UploadedVaccinationExcelFileEntryModel> = [];
  currentObject: ReferenceStationModel | ReferenceBusinessEntityModel;

  constructor(private dialog: MatDialog,
              public authService: AuthService,
              protected store: Store<CrudState>,
              private userObjectService: UserObjectListService,
              public usersService: UsersService) {
    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: this.type}));
  }

  ngOnInit(): void {
    this.getData();
    this.userObjectService.getCurrentObjectList().subscribe((res: [ReferenceStationModel
      | ReferenceBusinessEntityModel, Array<ReferenceStationModel | ReferenceBusinessEntityModel>, string]) => {
      this.currentObject = res[0];
    });
  }

  uploadFile(): void {
    const dialog = this.dialog.open(ModalVaccinationImportComponent, {
      width: '600px',
      height: '100% - 50px',
      autoFocus: false,
      data: {
        title: 'вакцинации',
        extension: 'xls',
        crud: CrudType.UploadEcxelVaccination,
        subTitle: this.currentObject ? this.currentObject.name : null,
        additionParams: this.currentObject ? this.currentObject.id : null
      }
    });

    dialog.afterClosed().subscribe(result => {
      if (result) {
        this.getData();
      }
    });
  }

  getData(): void {
    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: {
        fields: this.fields,
        order: this.order,
        offset: this.offset,
        limit: this.limit
      },
      onSuccess: (res) => {
        if (res.status === true && res.response.items) {
          this.excelFiles = res.response.items;
        }
      }
    }));
  }

  getVaccines(vaccineSeries: Array<VaccineSeriesModel>): string {
    let vaccines = '';
    if (vaccineSeries) {
      for (const item of vaccineSeries) {
        vaccines += item.vaccine.name + ' ,';
      }
      vaccines = vaccines.substring(0, vaccines.length - 1);
    }
    return vaccines;
  }

  getDoctor(people: Array<PersonModel>): string {
    let doctor = '';
    if (people && people.length) {
      doctor += people[0].surname + ' ' + people[0].name + ' ' + people[0].patronymic;
    }
    return doctor;
  }
  getStatus(rows: Array<ApiQueueRowModel>): string {
    if (!rows || rows.length === 0) {
      return null;
    }
    let statusCode = rows.find(n => n.status['code'] === 'saved')?.status['code'];
    if (!statusCode) {
      statusCode = rows.find(n => n.status['code'] === 'pending')?.status['code'];
      if (!statusCode) {
        statusCode = rows.find(n => n.status['code'] === 'finished_with_errors')?.status['code'];
        if (!statusCode) {
          statusCode = rows.find(n => n.status['code'] === 'finished')?.status['code'];
          if (!statusCode) {
            statusCode = rows.find(n => n.status['code'] === 'uploaded')?.status['code'];
          }
        }
      }
    }
    return rows.find(n => n.status['code'] === statusCode).status['title'];
  }
}
