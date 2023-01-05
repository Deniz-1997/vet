import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { DiseaseRoutingModule } from './disease-routing.module';
import { DiseaseListComponent } from './disease-list/disease-list.component';
import { DiseaseEditComponent } from './disease-edit/disease-edit.component';
import {SharedModule} from '../../../../../shared/shared.module';
import {ReferenceModule} from '../../../../../shared/modules/reference-edit/reference.module';


@NgModule({
  declarations: [DiseaseListComponent, DiseaseEditComponent],
    imports: [
        CommonModule,
        DiseaseRoutingModule,
        SharedModule,
        ReferenceModule,
    ]
})
export class DiseaseModule { }
