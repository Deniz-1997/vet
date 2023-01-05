import {Component, Input, OnInit} from '@angular/core';
import {FormTemplateModel} from '../../../../../../../models/form-template/form-template.models';
import {FormTemplateService} from '../../../../../../../services/form-template.service';

@Component({
  selector: 'app-form-template-view',
  templateUrl: './view.component.html',
  styleUrls: ['./view.component.css']
})

export class ViewComponent implements OnInit {

  @Input() formTemplate: FormTemplateModel;

  constructor(
    public formTemplateService: FormTemplateService
  ) {
  }

  ngOnInit() {

    if (this.formTemplate.fields && this.formTemplate.fields.length) {
      this.formTemplate.fields = this.formTemplate.fields.map(field => {
        const properties = field.formTemplateField.properties;
        const entityViewType = this.formTemplateService.findProperty(properties, 'entity_view_type');
        if (entityViewType) {
          field.viewType = entityViewType.value;
        }

        const multiSelect = this.formTemplateService.findProperty(properties, 'multi_select');
        if (multiSelect) {
          field.multiSelect = +multiSelect.value > 0;
        }

        const defaultValue = this.formTemplateService.findProperty(properties, 'entity_default_value');
        if (defaultValue) {
          field.defaultValue = defaultValue.value;
        }

        field.formTemplateField.extraData = {};

        if (['date', 'multi_date'].includes(field.formTemplateField.type.code)) {
          field.formTemplateField.extraData = this.formTemplateService.getDateExtraData(properties, field.formTemplateField.type.code);
        }
        return field;
      });
    }

    this.formTemplate.templateArr = this.formTemplate.template !== null
      ? this.formTemplateService.createTemplateArr(this.formTemplate.template)
      : [];
  }

  onChangeMinDate($event, field) {
    const limit = this.formTemplateService.findProperty(field.formTemplateField.properties, 'date_max_limit');
    const date = new Date($event.value);
    if (limit.value === 'gth') {
      date.setDate(date.getDate() + 1);
    }
    field.formTemplateField.extraData.dateMax.min = date;
    return;
  }
}
