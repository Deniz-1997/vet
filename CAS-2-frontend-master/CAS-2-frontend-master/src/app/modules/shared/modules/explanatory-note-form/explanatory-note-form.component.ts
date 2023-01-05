import {Component, ElementRef, EventEmitter, Input, OnInit, Output, ViewChild} from '@angular/core';
import {CrudType} from '../../../../common/crud-types';
import {select, Store} from '@ngrx/store';
import {FormControl, FormGroup} from '@angular/forms';
import { Observable} from 'rxjs';
import {SnackBarService} from 'src/app/services/snack-bar.service';
import {UploadedFileModel} from 'src/app/models/file/uploaded-file.models';
import {ReportExplanatoryNoteModel} from 'src/app/models/reference/reference.explanatory.note.models';
import {LoadCreateAction, LoadGetListAction} from '../../../../api/api-connector/crud/crud.actions';
import {getCrudModelGetListLoading} from '../../../../api/api-connector/crud/crud.selectors';
import {CrudState} from '../../../../api/api-connector/crud/crud-store.service';
import {DataType} from '../../../../api/api-connector/api-connector.utils';

@Component({
  selector: 'app-explanatory-note-form',
  templateUrl: './explanatory-note-form.component.html',
})

export class ExplanatoryNoteFormComponent implements OnInit {
  private _reportDataId: number;
  @Input('reportDataId')
  get reportDataId(): number {
    return this._reportDataId;
  }
  set reportDataId(value: number) {
    this._reportDataId = Number(value);
  }
  @ViewChild('fileInput') fileElement: ElementRef;
  @Output() explanatoryNoteComment: EventEmitter<any> = new EventEmitter();
  @Output() explanatoryNoteFile: EventEmitter<any> = new EventEmitter();
  @Output() submitReport: EventEmitter<any> = new EventEmitter();
  @Input() file: File;
  private _comment: string;
  @Input('comment')
  get comment(): string {
    return this._comment;
  }
  set comment(value: string) {
    if (this.formGroup !== undefined) {
      this.formGroup.controls['comment'].setValue(value);
    }
    this._comment = value;
  }
  formGroup: FormGroup = new FormGroup({
    comment: new FormControl()
  });
  loading$: Observable<boolean>;
  tableFields = ['name', 'file', 'user', 'date'];
  crudType: CrudType;
  dataList = new Array<ReportExplanatoryNoteModel>();

  constructor(
    private snackBar: SnackBarService,
    protected store: Store<CrudState>) {
    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: CrudType.ReportExplanatoryNote}));
  }

  ngOnInit(): void {
    this.formGroup.controls['comment'].valueChanges.subscribe(val => {
      this.explanatoryNoteComment.emit(val);
    });
    this.getList();
  }

  submit(): void {
    if (this.reportDataId === 0) {
      this.submitReport.emit('new');
      setTimeout(() => this.submit(), 400);
      return;
    }
    if (!this.file && !this.formGroup.controls.comment.value) {
      this.snackBar.handleMessage('Заполните комментарий или выберите файл', 'warning-snackBar', 2000);
      return;
    }
    if (this.file) {
      this.store.dispatch(new LoadCreateAction({
        type: CrudType.UploadedFile,
        params: {file: this.file},
        dataType: DataType.formData,
        onError: (err) => {
          this.snackBar.handleErrors(err.errors);
        },
        onSuccess: (res) => {
          if (res.status === true) {
            this.sendData(res.response);
          }
        }
      }));
    } else {
      this.sendData();
    }
  }

  sendData(uploadedFile: UploadedFileModel = null): void {
    const model = new ReportExplanatoryNoteModel();
    if (uploadedFile) {
      model.file = new UploadedFileModel();
      model.file.id = uploadedFile.id;
    }
    model.reportData = {id: this.reportDataId};
    model.comment = this.formGroup.controls.comment.value;
    this.store.dispatch(new LoadCreateAction({
      type: CrudType.ReportExplanatoryNote,
      params: model,
      onError: (err) => {
        this.snackBar.handleErrors(err.errors);
      },
      onSuccess: (res) => {
        this.snackBar.handleMessage('Пояснительная записка сохранена', 'success-snackBar', 2000);
        this.getList();
        this.formGroup.controls.comment.setValue(null);
        this.file = null;
      }
    }));
  }

  private getList(): void {
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReportExplanatoryNote,
      params: {
        filter: {reportData: {id: this.reportDataId}}
      },
      onSuccess: response => {
        this.dataList = response.response.items;
      }
    }));
  }
  chooseFile(event: Event): void {
    event.preventDefault();
    this.fileElement.nativeElement.click();
  }
  changeFile(event: Event): void {
    const files: FileList = event.target['files'];
    if (files && files.length > 0) {
      this.file = files[0];
      this.explanatoryNoteFile.emit(this.file);
    }
  }
}
