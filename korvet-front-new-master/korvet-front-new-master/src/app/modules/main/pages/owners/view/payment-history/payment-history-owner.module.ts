import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {PaymentHistoryOwnerRoutingModule} from './payment-history-owner-routing.module';
import {PaymentHistoryOwnerComponent} from './payment-history-owner.component';
import {SharedModule} from '../../../../../shared/shared.module';
import {CashReceiptHistoryModule} from '../../../cash/cash-receipt-history/cash-receipt-history.module';

@NgModule({
  declarations: [PaymentHistoryOwnerComponent],
    imports: [
        CommonModule,
        PaymentHistoryOwnerRoutingModule,
        SharedModule,
        CashReceiptHistoryModule,
    ]
})
export class PaymentHistoryOwnerModule {
}
