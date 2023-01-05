import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {FtpHistoryRoutingModule} from './ftp-history-routing.module';
import {ListComponent} from './list/list.component';
import {SharedModule} from '../../../../shared/shared.module';
import {ViewComponent} from './view/view.component';

@NgModule({
  declarations: [ListComponent, ViewComponent],
  imports: [
    CommonModule,
    SharedModule,
    FtpHistoryRoutingModule
  ]
})
export class FtpHistoryModule {
}
