import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {LeavingCreateRoutingModule} from './leaving-create-routing.module';
import {LeavingCreateComponent} from './leaving-create.component';
import {SharedModule} from '../../../../shared/shared.module';
import {LeavingAddFormModule} from '../../../../shared/modules/leaving-add-form/leaving-add-form.module';



@NgModule({
  declarations: [LeavingCreateComponent],
    imports: [
        CommonModule,
        LeavingAddFormModule,
        LeavingCreateRoutingModule,
        SharedModule
    ]
})
export class LeavingCreateModule { }
