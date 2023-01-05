import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AppointmentHistoryComponent } from './appointment-history.component';
import { SharedModule } from '../../shared.module';



@NgModule({
  declarations: [AppointmentHistoryComponent],
  imports: [
    CommonModule,
    SharedModule
  ],
  exports: [AppointmentHistoryComponent]
})
export class AppointmentHistoryModule { }
