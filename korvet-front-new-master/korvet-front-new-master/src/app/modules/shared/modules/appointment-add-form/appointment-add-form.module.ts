import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {AppointmentAddFormComponent} from './appointment-add-form.component';
import {SharedModule} from '../../shared.module';

@NgModule({
  declarations: [AppointmentAddFormComponent],
  imports: [
    CommonModule,
    SharedModule
  ],
  exports: [AppointmentAddFormComponent],
})
export class AppointmentAddFormModule {
}
