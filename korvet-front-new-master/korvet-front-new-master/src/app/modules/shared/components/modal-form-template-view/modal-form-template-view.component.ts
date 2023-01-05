import {Component, Inject, OnInit} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {FormGroup} from '@angular/forms';
import {Observable} from 'rxjs';
import {Store} from '@ngrx/store';
import {NotifyService} from '../../../../services/notify.service';
import {FormTemplateModel} from '../../../../models/form-template/form-template.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

declare var $: any;

@Component({
  selector: 'app-modal-form-template-view',
  templateUrl: './modal-form-template-view.component.html',
  styleUrls: ['./modal-form-template-view.component.css'],
})
export class ModalFormTemplateViewComponent implements OnInit {
  loading$: Observable<boolean>;
  formGroup: FormGroup;
  errors: boolean[];

  constructor(
    @Inject(MAT_DIALOG_DATA) public data: {
      head: string,
      headComment: string,
      buttonTitle: string,
      body: string,
      actions: {
        title: string,
        action: any,
        class: string
      }[],
      formTemplate: FormTemplateModel,
      template: string
    },
    private dialogRef: MatDialogRef<ModalFormTemplateViewComponent>,
    private store: Store<CrudState>,
    protected notify: NotifyService,
  ) {
    this.errors = new Array<boolean>(this.data.formTemplate.fields.length);
  }

  ngOnInit() {

  }

  submit() {
    const result: string[] = new Array<string>(this.data.formTemplate.fields.length);
    if (this.data.formTemplate && this.data.formTemplate.fields) {
      for (let i = 0; i < this.data.formTemplate.fields.length; i++) {
        const element = document.getElementById('input_' + this.data.formTemplate.fields[i].formTemplateField.type.code + '_' + i.toString());
        if (!element) {
          this.dialogRef.close(false);
        }
        if ((<string>element['value']).length === 0 && this.data.formTemplate.fields[i].isRequired) {
          this.errors[i] = true;
          return;
        }
        result[i] = element['value'];
      }
    }
    this.dialogRef.close(result);
  }
}
