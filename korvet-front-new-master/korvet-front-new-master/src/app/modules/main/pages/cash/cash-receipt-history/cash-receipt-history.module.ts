import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {CashReceiptHistoryComponent} from './cash-receipt-history.component';
import {SharedModule} from '../../../../shared/shared.module';
import {RouterModule} from '@angular/router';


@NgModule({
  declarations: [CashReceiptHistoryComponent],
  imports: [
    CommonModule,
    SharedModule,
    RouterModule,
  ],
  exports: [CashReceiptHistoryComponent]
})
export class CashReceiptHistoryModule {
}
