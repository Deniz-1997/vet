import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {HistoryRoutingModule} from './history-routing.module';
import {HistoryComponent} from './history.component';
import {SharedModule} from '../../../../../shared/shared.module';
import {LeavingHistoryModule} from '../leaving/leaving-history.module';

@NgModule({
  declarations: [
    HistoryComponent,
  ],
  imports: [
    CommonModule,
    HistoryRoutingModule,
    SharedModule,
    LeavingHistoryModule,
  ],
})
export class HistoryModule {
}
