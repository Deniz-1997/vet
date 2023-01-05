import {Component, Input, OnInit} from '@angular/core';
import {AppointmentModel} from '../../../../../../../models/appointment/appointment.models';
import {FormTemplateModel} from '../../../../../../../models/form-template/form-template.models';
import {Store} from '@ngrx/store';
import {forkJoin, of} from 'rxjs';
import {CrudType} from '../../../../../../../common/crud-types';
import {map} from 'rxjs/operators';
import {FormTemplateService} from '../../../../../../../services/form-template.service';
import {ApiParamsInterface} from 'src/app/api/api-connector/api-connector.models';
import {ApiConnectorService} from 'src/app/api/api-connector/api-connector.service';
import {CrudState, CrudStoreService} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({
  selector: 'app-form-template-appointment-view',
  templateUrl: './view.component.html',
  styleUrls: ['./view.component.css']
})
export class ViewComponent implements OnInit {

  @Input() appointment: AppointmentModel;
  @Input() readonly type = CrudType.Appointment;
  array;

  constructor(
    protected store: Store<CrudState>,
    protected connector: ApiConnectorService,
    protected crud: CrudStoreService,
    protected formTemplateService: FormTemplateService
  ) {
  }

  ngOnInit() {
    if (this.appointment.appointmentFormTemplate !== undefined) {
      forkJoin(this.appointment.appointmentFormTemplate.map(formTemplate => {
        if (formTemplate.formFieldValues && formTemplate.formFieldValues.length) {
          return forkJoin(formTemplate.formFieldValues.map(field => {
            if (field.formField && ['reference', 'template_reference'].includes(field.formField.formTemplateField.type.code)) {
              let crudType = null;
              field.formField.formTemplateField.properties.some(property => {
                const cond = property.formFieldProperty.code === 'entity';
                if (cond) {
                  crudType = property.value;
                }
                return cond;
              });

              if (field.formField.formTemplateField.type.code === 'template_reference') {
                crudType = CrudType.TemplateReferenceValues;
              }
              if (this.formTemplateService.isJSON(field.value)) {
                const params: ApiParamsInterface = {
                  order: {name: 'ASC'}, filter: {
                    id: JSON.parse(field.value),
                    deleted: '*',
                  }
                };
                return this.connector.getList(this.crud.config[crudType].setData(params).url, params)
                  .pipe(
                    map(response => {
                      if (response.response && response.response.items && response.response.items.length) {
                        return response.response.items.reduce(
                          (acc, item, i) => acc + (i > 0 ? ', ' : '') + item.name,
                          ''
                        );
                      }
                      return '';
                    })
                  );
              } else {
                return of(field.value);
              }
            } else {

              if (field.formField.formTemplateField.type.code === 'date') {
                if (this.formTemplateService.isJSON(field.value)) {
                  const value = JSON.parse(field.value);
                  const time = value.time ? value.time : '';
                  field.value = value.date ? value.date + ' ' + time : '';
                }
              }
              if (field.formField.formTemplateField.type.code === 'multi_date') {
                if (this.formTemplateService.isJSON(field.value)) {
                  const value = JSON.parse(field.value);
                  const dateMinTime = value.dateMinTime ? value.dateMinTime : '';
                  const dateMaxTime = value.dateMaxTime ? value.dateMaxTime : '';
                  field.value = value.dateMin + ' ' + dateMinTime + ' - ' + value.dateMax + ' ' + dateMaxTime;
                }
              }
              return of(field.value);
            }
          }));
        }
      })).forEach(v => {
        this.appointment.appointmentFormTemplate.map((formTemplate, a) => {
          if (formTemplate.formFieldValues && formTemplate.formFieldValues.length) {
            formTemplate.formFieldValues.map((field, i) => {
              this.appointment.appointmentFormTemplate[a].formFieldValues[i]['fieldValue'] = v[a][i];
            });
          }
        });
      });
    }
  }

  getInnerHTML(formTemplate: FormTemplateModel) {
    return formTemplate.template.split(new RegExp(/{{{.*?}}}/g));
  }
}
