import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {AppointmentsRoutingModule} from './appointments-routing.module';
import {AppointmentsComponent} from './appointments.component';
import {AddDialogComponent} from './add-dialog/add-dialog.component';
import {AppointmentAddFormModule} from '../../../shared/modules/appointment-add-form/appointment-add-form.module';

@NgModule({
  declarations: [AppointmentsComponent, AddDialogComponent],
  imports: [
    CommonModule,
    AppointmentAddFormModule,
    AppointmentsRoutingModule
  ],
  exports: [
    AppointmentsComponent,
  ],
  entryComponents: []
})
export class AppointmentsModule {
}
