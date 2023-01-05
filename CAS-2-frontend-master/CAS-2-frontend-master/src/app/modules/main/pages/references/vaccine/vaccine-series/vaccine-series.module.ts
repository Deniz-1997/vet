import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { VaccineSeriesRoutingModule } from './vaccine-series-routing.module';
import {ReferenceModule} from '../../../../../shared/modules/reference-edit/reference.module';
import {SharedModule} from '../../../../../shared/shared.module';
import {ReferenceEditModule} from '../../../../../shared/modules/reference-edit/edit/reference-edit.module';
import {ListComponent} from './list/list.component';
import {EditComponent} from './edit/edit.component';
import {ColModule, ListItemContentModule, RowModule, SubheaderModule, TextFieldModule} from '@korvet/ui-elements';



@NgModule({
  declarations: [ListComponent, EditComponent],
    imports: [
        CommonModule,
        VaccineSeriesRoutingModule,
        SharedModule,
        ReferenceModule,
        ReferenceEditModule,
        RowModule,
        ColModule,
        TextFieldModule,
        SubheaderModule,
        ListItemContentModule,
    ]
})
export class VaccineSeriesModule { }
