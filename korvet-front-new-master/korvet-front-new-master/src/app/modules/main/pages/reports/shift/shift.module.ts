import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {ShiftRoutingModule} from './shift-routing.module';
import {ShiftComponent} from './shift.component';
import {SharedModule} from '../../../../shared/shared.module';

@NgModule({
  declarations: [ShiftComponent],
  imports: [
    CommonModule,
    SharedModule,
    ShiftRoutingModule
  ]
})
export class ShiftModule {
}
