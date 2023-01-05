import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { MeasurementUnitsRoutingModule } from './measurement-units-routing.module';
import { MeasurementUnitsListComponent } from './measurement-units-list/measurement-units-list.component';
import { MeasurementUnitsEditComponent } from './measurement-units-edit/measurement-units-edit.component';
import {SharedModule} from '../../../../../shared/shared.module';
import {ReferenceModule} from '../../../../../shared/modules/reference-edit/reference.module';


@NgModule({
  declarations: [MeasurementUnitsListComponent, MeasurementUnitsEditComponent],
    imports: [
        CommonModule,
        MeasurementUnitsRoutingModule,
        SharedModule,
        ReferenceModule
    ]
})
export class MeasurementUnitsModule { }
