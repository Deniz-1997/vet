import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { VaccinationOldComponent } from './vaccination-old.component';
import {SharedModule} from 'src/app/modules/shared/shared.module';
import {VaccinationRoutingModule} from './vaccination-old-routing.module';
import {ButtonModule, ColModule, ContainerModule, HeaderModule, RowModule, SubheaderModule} from '@korvet/ui-elements';
import {FlexLayoutModule} from '@angular/flex-layout';
import {UniversalMatTableModule} from '../../../../shared/modules/universal-mat-table/universal-mat-table.module';
import {ViewOldComponent} from './view/view-old.component';



@NgModule({
  declarations: [VaccinationOldComponent, ViewOldComponent],
    imports: [
        CommonModule,
        SharedModule,
        VaccinationRoutingModule,
        ContainerModule,
        RowModule,
        ColModule,
        ButtonModule,
        FlexLayoutModule,
        HeaderModule,
        SubheaderModule,
        UniversalMatTableModule
    ]
})
export class VaccinationOldModule { }
