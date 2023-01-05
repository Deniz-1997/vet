import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { LeavingStatusRoutingModule } from './leaving-status-routing.module';
import { LeavingStatusEditComponent } from './leaving-status-edit/leaving-status-edit.component';
import { LeavingStatusListComponent } from './leaving-status-list/leaving-status-list.component';
import {SharedModule} from '../../../../../shared/shared.module';
import {ReferenceModule} from '../../../../../shared/modules/reference-edit/reference.module';


@NgModule({
  declarations: [LeavingStatusEditComponent, LeavingStatusListComponent],
    imports: [
        CommonModule,
        LeavingStatusRoutingModule,
        SharedModule,
        ReferenceModule
    ]
})
export class LeavingStatusModule { }
