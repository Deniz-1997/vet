import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {HistoryRoutingModule} from './history-routing.module';
import {ListComponent as ProductHistoryListComponent} from './list/list.component';
import {ViewComponent as ProductHistoryViewComponent} from './view/view.component';
import {SharedModule} from '../../../../../shared/shared.module';

@NgModule({
  declarations: [ProductHistoryListComponent, ProductHistoryViewComponent],
  imports: [
    CommonModule,
    SharedModule,
    HistoryRoutingModule
  ]
})
export class HistoryModule {
}
