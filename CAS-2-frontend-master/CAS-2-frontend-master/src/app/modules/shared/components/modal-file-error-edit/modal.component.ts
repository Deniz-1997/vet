import {Component, Inject, OnInit} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {MAT_DIALOG_DATA, MatDialog, MatDialogRef} from '@angular/material/dialog';
import {ApiQueueRowModel} from 'src/app/models/vaccination/api-queue-row.model';
import {DataNameService} from '../../../main/pages/report/vaccination/view/data-name.service';
import {Store} from '@ngrx/store';
import {CrudState} from '../../../../api/api-connector/crud/crud-store.service';
import {LoadPatchAction} from '../../../../api/api-connector/crud/crud.actions';
import {CrudType} from '../../../../common/crud-types';
import {ReportStatusService} from '../../../main/pages/report/report-services/report-status.service';

@Component({
  templateUrl: './modal.component.html',
  styleUrls: ['./modal.component.css'],
})
export class ModalFileErrorEditComponent implements OnInit {
  formGroup: FormGroup = new FormGroup({
    errorField: new FormControl(),
  });
  fields = {0: 'id', 1: 'data', 2: 'status', 3: 'error'};

  constructor(
    public dialog: MatDialog,
    @Inject(MAT_DIALOG_DATA) public data: ApiQueueRowModel,
    private fb: FormBuilder,
    public dataNameService: DataNameService,
    private reportStatusService: ReportStatusService,
    private dialogRef: MatDialogRef<ModalFileErrorEditComponent>,
    private store: Store<CrudState>
  ) {
    this.formGroup = this.fb.group({
      errorField: [this.GetDataElementValue(), [Validators.required]],
    });
  }

  ngOnInit(): void {
  }

  close(): void {
  }

  submit(): void {
    this.SetDataElementValue();
    const data = {...this.data} as ApiQueueRowModel;
    if (this.formGroup.valid) {
      data.status = 'saved';
      data.error = null;
      this.store.dispatch(new LoadPatchAction({
        type: CrudType.ApiQueueRow,
        params: data,
        fields: {fields: this.fields},
        onSuccess: ({response, status}) => {
          if (status && response) {
            this.dialogRef.close(response);
          }
        }
      }));
    }
  }

  private GetDataElementValue(): string {
    for (const item of Object.getOwnPropertyNames(this.data.data)) {
      if (item.toLowerCase() === (this.data.error.field as string).replace('_', '').toLowerCase()) {
        return this.data.data[item];
      }
    }
    return '';
  }

  private SetDataElementValue(): void {
    for (const item of Object.getOwnPropertyNames(this.data.data)) {
      if (item.toLowerCase() === (this.data.error.field as string).replace('_', '').toLowerCase()) {
        this.data.data[item] = this.formGroup.get('errorField').value;
      }
    }
  }
}
