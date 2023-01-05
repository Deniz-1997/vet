import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import {LeavingScheduleRoutingModule} from './leaving-schedule-routing.module';
import {LeavingScheduleComponent} from './leaving-schedule.component';
import {SharedModule} from '../../../../shared/shared.module';


@NgModule({
  declarations: [LeavingScheduleComponent],
  imports: [
    CommonModule,
    SharedModule,
    LeavingScheduleRoutingModule
  ]
})
export class LeavingScheduleModule { }
