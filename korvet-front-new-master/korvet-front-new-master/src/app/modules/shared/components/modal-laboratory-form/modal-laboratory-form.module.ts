import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {SharedModule} from '../../shared.module';
import { ModalLaboratoryFormComponent } from './modal-laboratory-form.component';
import { LaboratoryReferenceModule } from 'src/app/modules/main/pages/admin/laboratory/laboratory/laboratory-reference.module';



@NgModule({
  declarations: [ModalLaboratoryFormComponent],
  imports: [
    CommonModule,
    SharedModule,
    LaboratoryReferenceModule,
  ],
  exports: [ModalLaboratoryFormComponent]
})
export class ModalLaboratoryFormModule { }
