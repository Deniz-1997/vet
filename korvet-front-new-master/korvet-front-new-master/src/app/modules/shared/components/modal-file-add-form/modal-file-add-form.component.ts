import {Component, ElementRef, Inject, OnInit, ViewChild} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {select, Store} from '@ngrx/store';
import {AppointmentModel} from '../../../../models/appointment/appointment.models';
import {OwnerModel} from '../../../../models/owner/owner.models';
import {DataType} from '../../../../common/data-type';
import {PetModel} from '../../../../models/pet/pet.models';
import {combineLatest, Observable} from 'rxjs';
import {map, tap, timeout} from 'rxjs/operators';
import {NotifyService} from '../../../../services/notify.service';
import {allowedFileTypes} from '../../../../common/config';
import {MonitoredObjectModel} from '../../../../models/owner/monitored-object.models';
import {FileMonitoredObjectModel} from '../../../../models/file/file.monitored.object.models';
import {CrudType} from 'src/app/common/crud-types';
import {LeavingModel} from '../../../../models/leaving/leaving.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadCreateAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelCreateLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-modal-file-add-form',
  templateUrl: './modal-file-add-form.component.html',
  styleUrls: ['./modal-file-add-form.component.css']
})
export class ModalFileAddFormComponent implements OnInit {
  crudType = CrudType;
  formGroup: FormGroup;
  @ViewChild('file') fileElement: ElementRef;
  private type: CrudType.FileOwner | CrudType.File | CrudType.FileMonitoredObject;
  loading$: Observable<boolean>;
  private subject: 'pet' | 'owner' | 'monitoredObject' | 'leaving';
  showError = false;
  acceptTypes: string;
  getLoading: Observable<boolean>;

  constructor(
    @Inject(MAT_DIALOG_DATA) public data: {
      subject?: OwnerModel | PetModel | MonitoredObjectModel | LeavingModel,
      fileTypes$: Observable<FileMonitoredObjectModel[]>;
      appointments: AppointmentModel[];
      documentId?: string;
      leaving: LeavingModel[];
    },
    private dialogRef: MatDialogRef<ModalFileAddFormComponent>,
    private store: Store<CrudState>,
    protected notify: NotifyService
  ) {
    dialogRef.afterOpened().subscribe(() => {
      if (data.subject instanceof OwnerModel) {
        this.type = CrudType.FileOwner;
        this.subject = 'owner';
      } else if (data.subject instanceof MonitoredObjectModel) {
        this.type = CrudType.FileMonitoredObject;
        this.subject = 'monitoredObject';
      } else if (data.subject instanceof PetModel) {
        this.type = CrudType.File;
        this.subject = 'pet';
      } else {
        this.type = undefined;
        this.subject = 'pet';
      }
    });
  }

  ngOnInit() {
    this.acceptTypes = Object.keys(allowedFileTypes).join(',');
    this.formGroup = new FormGroup({
      name: new FormControl('', [Validators.required]),
      file: new FormControl(null, [Validators.required]),
    });

    if (typeof this.data.subject !== 'undefined') {
      this.formGroup.addControl('type', new FormControl(null, [Validators.required]));
    }

    if (this.data.appointments) {
      if (this.data.appointments.length === 1 && !(this.data.subject instanceof OwnerModel)) {
        this.formGroup.addControl('appointment', new FormControl(this.data.appointments[0].id));
      } else {
        this.formGroup.addControl('appointment', new FormControl(null));
      }

    }
    if (this.data.leaving) {
      if (this.data.leaving.length === 1 && !(this.data.subject instanceof LeavingModel)) {
        this.formGroup.addControl('leaving', new FormControl(this.data.leaving[0].id));
      } else {
        this.formGroup.addControl('leaving', new FormControl(null));
      }

    }
  }

  submit(): void {
  }

  chooseFile(event: Event): void {
    event.preventDefault();
    this.fileElement.nativeElement.click();
  }

  changeFile(event: Event): void {
    const files: FileList = event.target['files'];
    if (files && files.length > 0) {
      const fileName = files[0].name.replace(/\.[^/.]+$/, '');
      this.formGroup.patchValue({
        name: fileName
      });
    }
  }

  uploadFile(): void {
    this.showError = true;
    if (this.formGroup.valid) {
      const files: FileList = this.fileElement.nativeElement.files;
      const form = this.formGroup.value;

      const params = {
        file: files.item(0),
        name: null
      };

      if (typeof this.type === 'undefined') {
        params.name = this.formGroup.get('name').value;
      }
     
      this.store.dispatch(new LoadCreateAction({
        type: typeof this.type !== 'undefined' ? CrudType.UploadedFile : CrudType.PrintFormsList,
        params: params,
        dataType: DataType.formData,
        onSuccess: (res) => {
          const paramAppointment = (form['appointment'] ? {appointment: {id: form['appointment']}} : {});
          const paramLeaving = (form['leaving'] ? {leaving: {id: form['leaving']}} : {});
          let param;
          if (this.data.leaving) {
            param = paramLeaving;
          } else {
            param = paramAppointment;
          }
          this.notify.handleMessage('Файл успешно загружен на сервер', 'success');
          if (typeof this.type === 'undefined') {
            this.dialogRef.close(true);
          } else {
            this.getLoading = this.store.pipe(select(getCrudModelCreateLoading, {type: this.type}));
            let params_file = {
              name: form['name'],
              type: {id: form['type'].id},
              uploadedFile: {id: res.response.id},
              [this.subject]: {id: this.data.subject.id},
            }
            if (this.data.documentId) {
              params_file['documentId'] = this.data.documentId;
            }
            this.store.dispatch(new LoadCreateAction({
              type: this.type,
              params: <any>{
                ...params_file,
                ...param,
              },
              onSuccess: () => {
                this.showError = false;
                this.dialogRef.close(true);
              },
              onError: e => {
                this.notify.handleMessage('Произошла ошибка', 'danger', 5000);
                this.showError = true;
                this.dialogRef.close(true);
              }
            }));
          }
        },
      }));
      this.loading$ = combineLatest([
        this.store.pipe(select(getCrudModelCreateLoading, {type: CrudType.PrintFormsList})),
        this.store.pipe(select(getCrudModelCreateLoading, {type: CrudType.UploadedFile})),
        this.store.pipe(select(getCrudModelCreateLoading, {type: this.type})),
      ]).pipe(
        map(([upload, create]) => upload || create),
        tap(loading => this.dialogRef.disableClose = loading)
      );
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
    }
  }
}
