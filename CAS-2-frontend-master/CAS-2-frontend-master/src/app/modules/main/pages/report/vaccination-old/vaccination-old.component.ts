import {Component, OnInit} from '@angular/core';
import {MatDialog} from '@angular/material/dialog';
import {select, Store} from '@ngrx/store';
import {Observable} from 'rxjs';
import {CrudType} from 'src/app/common/crud-types';
import {PersonModel} from 'src/app/models/auth/person.model';
import {VaccineSeriesModel} from 'src/app/models/dictionary/vaccine-series.model';
import {UploadedVaccinationExcelFileEntryModel} from 'src/app/models/Import/uploaded-vaccination-excel-file-entry.model';
import {ModalFileUploadComponent} from 'src/app/modules/shared/components/modal-file-upload/modal.component';
import {getCrudModelGetListLoading} from '../../../../../api/api-connector/crud/crud.selectors';
import {CrudState} from '../../../../../api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from '../../../../../api/api-connector/crud/crud.actions';
import {ReferenceBusinessEntityModel} from 'src/app/models/reference/reference.businessEntity.models';
import {ReferenceStationModel} from 'src/app/models/reference/reference.station.models';
import {UserObjectListService} from 'src/app/services/user-object-list.service';

@Component({
  selector: 'app-vaccination',
  templateUrl: './vaccination-old.component.html',
  styleUrls: ['./vaccination-old.component.css']
})
export class VaccinationOldComponent implements OnInit {
  public loading$: Observable<boolean>;
  type = CrudType.ImportExcelFile;
  offset = 0;
  limit = 50;
  fields = {0: 'id', 1: 'statusCode', 2: 'statusMsg', 3: 'station', 4: 'sourceName', 5: 'uploadedAt'};
  order: {uploadedAt: 'DESC'};
  displayedColumns = ['date', 'file', 'station', 'state'];
  isEmptyInformation = 'Не найдено ни одного импорта вакцинаций. Вы можете добавить импорт вакцинаций';
  public excelFiles: Array<UploadedVaccinationExcelFileEntryModel> = [];
  currentObject: ReferenceStationModel | ReferenceBusinessEntityModel;

  constructor(private dialog: MatDialog,
              protected store: Store<CrudState>,
              private userObjectService: UserObjectListService) {

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
    const dialog = this.dialog.open(ModalFileUploadComponent, {
      width: '500px',
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
}
