import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {ListComponent} from './list/list.component';
import {EditComponent} from './edit/edit.component';
import {SharedModule} from '../../../../shared/shared.module';
import {PeriodComponent} from './period/period.component';

@NgModule({
  declarations: [ListComponent, EditComponent, PeriodComponent],
  imports: [
    CommonModule,
    SharedModule,
    RoutingModule
  ]
})
export class MainModule {
}
