import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {PaymentHistoryOwnerComponent} from './payment-history-owner.component';

const routes: Routes = [{
  path: '',
  component: PaymentHistoryOwnerComponent,
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PaymentHistoryOwnerRoutingModule {
}
