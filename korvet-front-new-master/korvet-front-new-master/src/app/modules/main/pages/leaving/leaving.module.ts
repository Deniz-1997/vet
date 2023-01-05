import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { LeavingRoutingModule } from './leaving-routing.module';
import { LeavingComponent } from './leaving.component';
import {LeavingAddFormModule} from '../../../shared/modules/leaving-add-form/leaving-add-form.module';
import {LeavingAddDialogComponent} from './leaving-add-dialog/leaving-add-dialog.component';


@NgModule({
  declarations: [LeavingComponent, LeavingAddDialogComponent],
  imports: [
    CommonModule,
    LeavingAddFormModule,
    LeavingRoutingModule
  ],
  exports: [
    LeavingComponent
  ],
  entryComponents: []
})
export class LeavingModule { }
