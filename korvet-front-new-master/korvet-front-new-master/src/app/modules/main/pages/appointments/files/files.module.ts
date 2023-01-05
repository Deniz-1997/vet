import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AppointmentFilesComponent } from './files.component';
import { SharedModule } from 'src/app/modules/shared/shared.module';



@NgModule({
  declarations: [AppointmentFilesComponent],
  imports: [
    CommonModule,
    SharedModule,
  ],
  exports: [
    AppointmentFilesComponent,
  ],
})
export class AppointmentFilesModule { }
