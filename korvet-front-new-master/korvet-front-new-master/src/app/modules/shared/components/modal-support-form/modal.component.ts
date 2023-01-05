import { Component, ElementRef, Inject, OnInit, ViewChild } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import {MAT_DIALOG_DATA, MatDialog, MatDialogRef} from '@angular/material/dialog';
import { Store } from '@ngrx/store';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadCreateAction} from 'src/app/api/api-connector/crud/crud.actions';
import { CrudType } from 'src/app/common/crud-types';
import {DataType} from 'src/app/common/data-type';
import { SupportMessageModel } from 'src/app/models/support/support-message.model';
import { NotifyService } from 'src/app/services/notify.service';
import {DialogElements} from './dialog-elements/dialog-elements';

@Component({
  selector: 'modal-support-form',
  templateUrl: './modal.component.html',
  styleUrls: ['./modal.component.css'],
})
export class ModalSupportFormComponent implements OnInit {
  formGroup: FormGroup;
  imageData: string;
  file: File;
  loading: boolean = false;
  @ViewChild('file') fileElement: ElementRef;

  constructor(
    public dialog: MatDialog,
    @Inject(MAT_DIALOG_DATA) public data: {
    },
    private fb: FormBuilder,
    private dialogRef: MatDialogRef<ModalSupportFormComponent>,
    private notify: NotifyService,
    private store: Store<CrudState>
  ) {
  }

  ngOnInit() {
    this.formGroup = this.fb.group({
      message: new FormControl('', [Validators.required]),
      file: new FormControl(null, []),
    });
  }

  close() {
    this.dialogRef.close();
  }

  chooseFile(event: Event): void {

    event.preventDefault();
    this.fileElement.nativeElement.click();
  }

  changeFile(event: Event): void {
    const files: FileList = event.target['files'];
    if (files && files.length > 0 && files[0].type.indexOf('image') >= 0) {
      this.file = files[0];
      var myReader: FileReader = new FileReader();

      myReader.onloadend = (e) => {
        this.imageData = myReader.result.toString();
      }
      myReader.readAsDataURL(this.file);
    }
    else {
      delete this.file;
    }
  }

  submit() {
    if (this.formGroup.valid) {
      this.loading = true;
      if (this.file) {
        this.store.dispatch(new LoadCreateAction({
          type: CrudType.UploadedFile,
          params: {file: this.file},
          dataType: DataType.formData,
          onError: (err) => {
            this.notify.handleErrors(err.errors);
            this.loading = false;
          },
          onSuccess: (res) => {
            if (res.status == true) {
              this.sendData(res.response.path + '/' + res.response.name);
            }
          }
        }));
      }
      else {
        this.sendData();
      }
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
    }
  }
  sendData(filePath: string = null){
    const model = new SupportMessageModel();
      if (filePath) {
        model.filePath = filePath;
      }
      model.message = this.formGroup.controls.message.value;
      model.url = window.location.href;
    this.store.dispatch(new LoadCreateAction({
      type: CrudType.SupportMessage,
      params: model,
      onError: (err) => {
        this.notify.handleErrors(err.errors);
        this.loading = false;
      },
      onSuccess: (res) => {
        this.loading = false;
        this.dialog.open(DialogElements);
        this.notify.handleMessage(res.response, 'success');
        this.dialogRef.close();
      }
    }));
  }
}
