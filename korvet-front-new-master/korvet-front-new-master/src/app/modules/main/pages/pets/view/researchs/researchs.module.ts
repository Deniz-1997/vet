import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {SharedModule} from '../../../../../shared/shared.module';
import { ResearchsComponent } from './researchs.component';
import { ResearchsRoutingModule } from './researchs-routing.module';
import { LaboratoryModule } from '../../../laboratory/laboratory.module';

@NgModule({
  declarations: [ResearchsComponent],
  imports: [
    ResearchsRoutingModule,
    SharedModule,
    CommonModule,
    LaboratoryModule
  ]
})
export class ResearchsModule {
}
