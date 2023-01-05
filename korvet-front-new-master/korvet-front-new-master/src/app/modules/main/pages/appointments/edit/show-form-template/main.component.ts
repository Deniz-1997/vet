import {Component, EventEmitter, Inject, Input, OnInit} from '@angular/core';
import {MAT_DIALOG_DATA} from '@angular/material/dialog';
import {Observable} from 'rxjs';
import {FormTemplateModel} from '../../../../../../models/form-template/form-template.models';
import {FormTemplateService} from '../../../../../../services/form-template.service';

@Component({
  templateUrl: './html.component.html',
  styleUrls: ['./style.component.css']
})
export class MainComponent implements OnInit {

  template: any;
  loading$: Observable<boolean>;

  @Input() addFormTemplate: EventEmitter<any> = new EventEmitter();

  constructor(
    @Inject(MAT_DIALOG_DATA) public data: {
      head: string,
      template: FormTemplateModel,
      actions: {
        title: string,
        action: any,
        class: string
      }[]
    },
    public formTemplateService: FormTemplateService
  ) {
  }

  ngOnInit() {
    this.template = this.data.template.template.split(new RegExp(/{{{.*?}}}/g));
    this.data.template.fields.map((formField, index) => {
      const extraData = formField.formTemplateField.extraData ? formField.formTemplateField.extraData : null;
      formField.formTemplateField.properties.map(property => {
        if (property.formFieldProperty.code === 'entity_default_value') {
          this.data.template.fields[index].defaultValue = property.value;
        }
        if (property.formFieldProperty.code === 'multi_select') {
          this.data.template.fields[index].multiSelect = +property.value > 0;
        }
      });
      if (extraData) {
        this.data.template.fields[index].parentReference = extraData.parentReference ? extraData.parentReference : null;
        this.data.template.fields[index].subParentReference = extraData.subParentReference ? extraData.subParentReference : null;
        this.data.template.fields[index].crudType = extraData.crudType ? extraData.crudType : null;
        this.data.template.fields[index].viewType = extraData.viewType ? extraData.viewType : null;
        this.data.template.fields[index].parentRefName = extraData.parentRefName ? extraData.parentRefName : null;
        this.data.template.fields[index].filter = extraData.filter ? extraData.filter : null;
      }

      if (['date', 'multi_date'].includes(formField.formTemplateField.type.code)) {
        this.data.template.fields[index].formTemplateField.extraData = this.formTemplateService.getDateExtraData(
          formField.formTemplateField.properties,
          formField.formTemplateField.type.code
        );
      }
      return formField;
    });
  }
}
