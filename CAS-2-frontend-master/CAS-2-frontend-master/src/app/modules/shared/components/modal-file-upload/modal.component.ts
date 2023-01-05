import {Component, ElementRef, Inject, OnInit, ViewChild} from '@angular/core';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {MAT_DIALOG_DATA, MatDialog, MatDialogRef} from '@angular/material/dialog';
import {select, Store} from '@ngrx/store';
import {Observable} from 'rxjs';
import {CrudType} from 'src/app/common/crud-types';
import {SnackBarService} from '../../../../services/snack-bar.service';
import {LoadCreateAction} from '../../../../api/api-connector/crud/crud.actions';
import {CrudState} from '../../../../api/api-connector/crud/crud-store.service';
import {getCrudModelCreateLoading} from '../../../../api/api-connector/crud/crud.selectors';
import {DataType} from '../../../../api/api-connector/api-connector.utils';
import {UserObjectListService} from 'src/app/services/user-object-list.service';
import {ReferenceStationModel} from 'src/app/models/reference/reference.station.models';
import {ReferenceBusinessEntityModel} from 'src/app/models/reference/reference.businessEntity.models';

@Component({
  templateUrl: './modal.component.html',
  styleUrls: ['./modal.component.css'],
})
export class ModalFileUploadComponent implements OnInit {
  formGroup: FormGroup;
  file: File;
  loading$: Observable<boolean>;
  @ViewChild('file') fileElement: ElementRef;

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
    protected dialogRef: MatDialogRef<ModalFileUploadComponent>,
  ) {
  }

  ngOnInit(): void {
    this.formGroup = this.fb.group({
      file: new FormControl(null, []),
    });
    this.loading$ = this.store.pipe(select(getCrudModelCreateLoading, {type: this.data.crud}));
  }

  close(): void {
  }

  chooseFile(event: Event): void {

    event.preventDefault();
    this.fileElement.nativeElement.click();
  }

  changeFile(event: Event): void {
    const files: FileList = event.target['files'];
    if (files && files.length > 0 && files[0].name.indexOf(this.data?.extension) >= 0) {
      this.file = files[0];
    } else {
      this.snackBar.handleMessage('Для загрузки выберите файл с расширением .' + this.data.extension, 'warning-snackBar', 2000);
      delete this.file;
    }
  }

  submit(): void {
    if (this.formGroup.valid && this.file) {
      const params = {
        file: this.file,
      };
      if (this.data.additionParams) {
        params['params'] = this.data.additionParams;
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
      this.snackBar.handleMessage('Не выбран файл', 'danger-snackBar', 2000);
    }
  }
}
