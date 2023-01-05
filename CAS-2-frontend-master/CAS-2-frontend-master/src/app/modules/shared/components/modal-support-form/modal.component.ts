import {Component, ElementRef, Inject, OnInit, ViewChild} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {MAT_DIALOG_DATA, MatDialog, MatDialogRef} from '@angular/material/dialog';
import {Store} from '@ngrx/store';
import {CrudType} from 'src/app/common/crud-types';
import {SupportMessageModel} from 'src/app/models/support/support-message.model';
import {DialogElementsComponent} from './dialog-elements/dialog-elements';
import {SnackBarService} from '../../../../services/snack-bar.service';
import {allowedFileTypes} from '../../../../common/config';
import {LoadCreateAction} from '../../../../api/api-connector/crud/crud.actions';
import {CrudState} from '../../../../api/api-connector/crud/crud-store.service';
import {DataType} from '../../../../api/api-connector/api-connector.utils';
import {AuthService} from 'src/app/api/auth/auth.service';

@Component({
  selector: 'app-modal-support-form',
  templateUrl: './modal.component.html',
  styleUrls: ['./modal.component.css'],
})
export class ModalSupportFormComponent implements OnInit {
  formGroup: FormGroup;
  imageData: string;
  file: File;
  loading = false;
  acceptTypes: string;
  @ViewChild('file') fileElement: ElementRef;

  constructor(
    public dialog: MatDialog,
    @Inject(MAT_DIALOG_DATA) public data: {
    },
    private fb: FormBuilder,
    private dialogRef: MatDialogRef<ModalSupportFormComponent>,
    protected snackBar: SnackBarService,
    private store: Store<CrudState>,
    private userService: AuthService,
  ) {
  }

  ngOnInit(): void {
    this.acceptTypes = Object.keys(allowedFileTypes).join(',');
    this.formGroup = this.fb.group({
      message: new FormControl('', [Validators.required]),
      phone: new FormControl(''),
      file: new FormControl(null, []),
    });
    this.userService.getAccountUser().subscribe(res => {
      if (res.response && res.status && res.response.user) {
        this.formGroup.controls['phone'].setValue(res.response.user['phoneNumber']);
      }
    });

  }


  close(): void {
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
      this.fileElement.nativeElement.value = '';
      const myReader: FileReader = new FileReader();

      myReader.onloadend = (e) => {
        this.imageData = myReader.result.toString();
      };
      myReader.readAsDataURL(this.file);
    } else {
      delete this.file;
    }
  }

  clearImage(): void {
    this.imageData = null;
  }

  submit(): void {
    if (this.formGroup.valid) {
      this.loading = true;
      if (this.file) {
        this.store.dispatch(new LoadCreateAction({
          type: CrudType.UploadedFile,
          params: {file: this.file},
          dataType: DataType.formData,
          onError: (err) => {
            this.snackBar.handleErrors(err.errors);
            this.loading = false;
          },
          onSuccess: (res) => {
            if (res.status === true) {
              this.sendData(res.response.path + '/' + res.response.name);
            }
          }
        }));
      } else {
        this.sendData();
      }
    } else {
      this.snackBar.handleMessage('Заполните обязательные поля', 'warning-snackBar', 2000);
    }
  }

  sendData(filePath: string = null): void {
    const model = new SupportMessageModel();
    if (filePath) {
      model.filePath = filePath;
    }
    if (this.formGroup.controls['phone'].value) {
      model.phone = this.formGroup.controls['phone'].value;
    }
    model.message = this.formGroup.controls.message.value;
    model.url = window.location.href;
    this.store.dispatch(new LoadCreateAction({
      type: CrudType.SupportMessage,
      params: model,
      onError: (err) => {
        this.snackBar.handleErrors(err.errors);
        this.loading = false;
      },
      onSuccess: (res) => {
        this.loading = false;
        this.dialog.open(DialogElementsComponent, {autoFocus: false});
        this.snackBar.handleMessage(res.response, 'success-snackBar', 2000);
        this.dialogRef.close();
      }
    }));
  }
}
