import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { VaccinationsComponent } from './vaccinations/vaccinations.component';
import {PublicRoutingModule} from './public-routing.module';
import {SharedModule} from '../shared/shared.module';
import {ButtonModule, ColModule, ContainerModule, RowModule, TextFieldModule} from '@korvet/ui-elements';
import {FlexLayoutModule} from '@angular/flex-layout';
import {UniversalMatTableModule} from '../shared/modules/universal-mat-table/universal-mat-table.module';



@NgModule({
  declarations: [VaccinationsComponent],
    imports: [
        CommonModule,
        PublicRoutingModule,
        SharedModule,
        ContainerModule,
        RowModule,
        ColModule,
        TextFieldModule,
        ButtonModule,
        FlexLayoutModule,
        UniversalMatTableModule
    ]
})
export class PublicModule { }
