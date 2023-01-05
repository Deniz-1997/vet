import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ReasonForLeavingRoutingModule } from './reason-for-leaving-routing.module';
import { ReasonForLeavingListComponent } from './reason-for-leaving-list/reason-for-leaving-list.component';
import { ReasonForLeavingEditComponent } from './reason-for-leaving-edit/reason-for-leaving-edit.component';
import {SharedModule} from '../../../../../shared/shared.module';
import {ReferenceModule} from '../../../../../shared/modules/reference-edit/reference.module';


@NgModule({
  declarations: [ReasonForLeavingListComponent, ReasonForLeavingEditComponent],
    imports: [
        CommonModule,
        ReasonForLeavingRoutingModule,
        SharedModule,
        ReferenceModule
    ]
})
export class ReasonForLeavingModule { }
