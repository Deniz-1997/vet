import { Injectable } from '@angular/core';
import {Store} from '@ngrx/store';
import {CrudType} from '../../../../../common/crud-types';
import {CrudState} from '../../../../../api/api-connector/crud/crud-store.service';
import {LoadCreateAction} from '../../../../../api/api-connector/crud/crud.actions';
import {DataType} from '../../../../../api/api-connector/api-connector.utils';
import {UploadedFileModel} from '../../../../../models/file/uploaded-file.models';
import {ReportExplanatoryNoteModel} from '../../../../../models/reference/reference.explanatory.note.models';
import {SnackBarService} from '../../../../../services/snack-bar.service';

@Injectable()
export class ExplanatoryNoteService {

  constructor(protected store: Store<CrudState>, private snackBar: SnackBarService) { }

  submitExplanatoryNote(file: File | undefined, comment: string | undefined, id: number): void {
    if (file) {
      this.store.dispatch(new LoadCreateAction({
        type: CrudType.UploadedFile,
        params: {file: file},
        dataType: DataType.formData,
        onError: (err) => {
          this.snackBar.handleErrors(err.errors);
        },
        onSuccess: (res) => {
          if (res.status === true) {
            this.sendData(res.response, comment, id);
          }
        }
      }));
    } else {
      this.sendData(null, comment, id);
    }
  }

  private sendData(uploadedFile: UploadedFileModel = null, comment: string, id: number): void {
    const model = new ReportExplanatoryNoteModel();
    if (uploadedFile) {
      model.file = new UploadedFileModel();
      model.file.id = uploadedFile.id;
    }
    model.reportData = {id: id};
    model.comment = comment;
    this.store.dispatch(new LoadCreateAction({
      type: CrudType.ReportExplanatoryNote,
      params: model,
      onError: (err) => {
        this.snackBar.handleErrors(err.errors);
      },
      onSuccess: _ => {
        setTimeout(() => this.snackBar.handleMessage('Пояснительная записка сохранена', 'success-snackBar', 1000), 1500);
      }
    }));
  }
}
