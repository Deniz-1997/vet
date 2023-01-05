import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {ScheduleComponent} from './schedule/schedule.component';
import {SharedModule} from '../../../../shared/shared.module';

@NgModule({
  declarations: [ScheduleComponent],
  imports: [
    CommonModule,
    SharedModule,
    RoutingModule
  ]
})
export class ScheduleModule {
}
