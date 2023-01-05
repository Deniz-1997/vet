import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {FormRoutingModule} from './form-routing.module';
import {FormComponent} from './form.component';
import {AppointmentAddFormModule} from '../../../../../../shared/modules/appointment-add-form/appointment-add-form.module';

@NgModule({
  declarations: [FormComponent],
  imports: [
    CommonModule,
    FormRoutingModule,
    AppointmentAddFormModule,
  ]
})
export class FormModule {
}
