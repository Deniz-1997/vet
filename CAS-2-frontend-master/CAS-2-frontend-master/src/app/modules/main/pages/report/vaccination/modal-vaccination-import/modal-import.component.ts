import {Component, Inject, OnInit} from '@angular/core';
import {FormBuilder, FormControl, Validators} from '@angular/forms';
import {MAT_DIALOG_DATA, MatDialog, MatDialogRef} from '@angular/material/dialog';
import {select, Store} from '@ngrx/store';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadCreateAction, LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelCreateLoading} from 'src/app/api/api-connector/crud/crud.selectors';
import {CrudType} from 'src/app/common/crud-types';
import {DataType} from 'src/app/common/data-type';
import {ReferenceBusinessEntityModel} from 'src/app/models/reference/reference.businessEntity.models';
import {ReferenceStationModel} from 'src/app/models/reference/reference.station.models';
import {ModalFileUploadComponent} from 'src/app/modules/shared/components/modal-file-upload/modal.component';
import {SnackBarService} from 'src/app/services/snack-bar.service';
import {UserObjectListService} from 'src/app/services/user-object-list.service';

@Component({
  templateUrl: './modal-import.component.html',
  styleUrls: ['./modal-import.component.css'],
})
export class ModalVaccinationImportComponent extends ModalFileUploadComponent implements OnInit {
  stationSupervisedFields = {0: 'id', 1: 'name', 2: 'address', 'station': {0: 'id'}};
  crudType = CrudType;
  supervisedFilter: any;
  subdivisionFilter: any;
  beId: number;
  stationId: number;
  constructor(
    public dialog: MatDialog,
    @Inject(MAT_DIALOG_DATA) public data: {
      title?: string,
      extension: string,
      crud: CrudType,
      uploadFileForExplanatoryNote: boolean,
      subTitle: string,
      additionParams: any
    },
    protected fb: FormBuilder,
    protected snackBar: SnackBarService,
    protected store: Store<CrudState>,
    protected dialogRef: MatDialogRef<ModalVaccinationImportComponent>,
    private userObjectService: UserObjectListService
  ) {
    super(dialog, data, fb, snackBar, store, dialogRef);
  }

  ngOnInit(): void {
    this.formGroup = this.fb.group({
      file: new FormControl(null, []),
      supervisedObjects: new FormControl(null, [Validators.required]),
      subdivisionObjects: new FormControl(null, [])
    });
    if (this.data.additionParams) {
      this.beId = this.data.additionParams;
    }
    this.loading$ = this.store.pipe(select(getCrudModelCreateLoading, {type: this.data.crud}));
    this.userObjectService.getCurrentObjectList().subscribe((res: [ReferenceStationModel | ReferenceBusinessEntityModel,
      Array<ReferenceStationModel | ReferenceBusinessEntityModel>, string]) => {
      if (res[0] instanceof ReferenceStationModel) {
        this.supervisedFilter = {station: {id: res[0].id}};
        this.subdivisionFilter = {station: {id: res[0].id}};
        this.SetSubdivisionObject();
      } else {
        this.supervisedFilter = {businessEntity: {id: res[0].id}};
        this.data.subTitle = res[0].name;
        this.beId = res[0].id;
        this.SetSupervisedObject();
      }
    });
  }

  private SetSupervisedObject(): void {
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceSupervisedObject,
      params: {
        filter: {'businessEntity': {'id': this.beId}},
        fields: this.stationSupervisedFields
      },
      onSuccess: ({status, response}) => {
        if (response && status && response && response.items && response.items.length === 1) {
          this.formGroup.controls['supervisedObjects'].setValue(response.items[0]);
          this.subdivisionFilter = {station: {id: response.items[0]['station']['id']}};
          this.SetSubdivisionObject();
        }
      }
    }));
  }

  private SetSubdivisionObject(): void {
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceSubdivision,
      params: {
        filter: this.subdivisionFilter,
        fields: {0: 'id', 1: 'name'}
      },
      onSuccess: ({status, response}) => {
        if (response && status && response && response.items && response.items.length === 1) {
          this.formGroup.controls['subdivisionObjects'].setValue(response.items[0]);
        }
      }
    }));
  }

  submit(): void {
    if (this.formGroup.valid && this.file) {
      const params = {
        file: this.file,
      };
      if (this.beId) {
        params['beId'] = this.beId;
      }
      if (this.formGroup.controls['supervisedObjects'].value && this.formGroup.controls['supervisedObjects'].value['id']) {
        params['supervisedObjectId'] = this.formGroup.controls['supervisedObjects'].value['id'];
      }
      if (this.formGroup.controls['subdivisionObjects'].value && this.formGroup.controls['subdivisionObjects'].value['id']) {
        params['subdivisionObjectId'] = this.formGroup.controls['subdivisionObjects'].value['id'];
      }
      this.store.dispatch(new LoadCreateAction({
        type: this.data.crud,
        params: params as any,
        dataType: DataType.formData,
        onSuccess: (res) => {
          if (res && res.status) {
            this.snackBar.handleMessage('Файл успешно загружен', 'success-snackBar', 2000);
            this.dialogRef.close(true);
          }
          else {
            this.dialogRef.close(false);
          }
        },
        onError: (res => {
          this.dialogRef.close(false);
        })
      }));
    } else {
      if (!this.file) {
        this.snackBar.handleMessage('Не выбран файл', 'warning-snackBar', 2000);
      }
      else {
        this.snackBar.handleMessage('Заполните обязательные поля', 'warning-snackBar', 2000);
      }
    }
  }

  supervisedSelected(event: any): void {
    if (event && event['station']) {
      this.subdivisionFilter = {station: {id: event['station']['id']}};
      this.formGroup.controls['subdivisionObjects'].setValue(null);
    }
  }
  getButtonName(): string {
    return this.file && this.file.name ? 'Файл: ' + this.file.name : 'Выбрать файл';
  }
}
