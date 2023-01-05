import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {PaymentHistoryPetsRoutingModule} from './payment-history-pets-routing.module';
import {PaymentHistoryPetsComponent} from './payment-history-pets.component';
import {SharedModule} from '../../../../../shared/shared.module';
import {CashReceiptHistoryModule} from '../../../cash/cash-receipt-history/cash-receipt-history.module';

@NgModule({
  declarations: [PaymentHistoryPetsComponent],
    imports: [
        CommonModule,
        PaymentHistoryPetsRoutingModule,
        SharedModule,
        CashReceiptHistoryModule,
    ]
})
export class PaymentHistoryPetsModule {
}
