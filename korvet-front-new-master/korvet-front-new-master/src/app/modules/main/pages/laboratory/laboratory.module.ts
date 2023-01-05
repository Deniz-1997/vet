import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {SharedModule} from '../../../shared/shared.module';
import { ProbeSamplingListComponent } from './probe-sampling/list/list.component';
import { LaboratoryRoutingModule } from './laboratory-routing.module';
import { ProbeSamplingComponent } from './probe-sampling/edit/edit.component';
import { ResearchDocumentListComponent } from './research/list/list.component';
import { ResearchDocumentComponent } from './research/edit/edit.component';
import { ProductListModule } from '../store/product-list/product-list.module';
import { ModalLaboratoryFormModule } from 'src/app/modules/shared/components/modal-laboratory-form/modal-laboratory-form.module';
import { ResearchHistoryComponent } from './research/history/history.component';
import { ResearchResultComponent } from './research/result/result.component';
import { ResearchHeaderComponent } from './research/research-header/research-header.component';
import { ModalProbeSamplingFormComponent } from './modal-probe-sampling-form/modal-probe-sampling-form.component';



@NgModule({
  declarations: [
    ProbeSamplingListComponent, 
    ProbeSamplingComponent, 
    ResearchDocumentListComponent, 
    ResearchDocumentComponent, 
    ResearchHistoryComponent,
    ResearchResultComponent,
    ResearchHeaderComponent, ModalProbeSamplingFormComponent],
  imports: [
    CommonModule,
    SharedModule,
    LaboratoryRoutingModule,
    ProductListModule,
    ModalLaboratoryFormModule
  ],
  exports: [ResearchResultComponent, ResearchHeaderComponent, ProbeSamplingComponent]
})
export class LaboratoryModule { }
