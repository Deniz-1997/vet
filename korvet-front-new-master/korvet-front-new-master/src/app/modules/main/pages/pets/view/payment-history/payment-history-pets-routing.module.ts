import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {PaymentHistoryPetsComponent} from './payment-history-pets.component';

const routes: Routes = [{
  path: '',
  component: PaymentHistoryPetsComponent,
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PaymentHistoryPetsRoutingModule {
}
