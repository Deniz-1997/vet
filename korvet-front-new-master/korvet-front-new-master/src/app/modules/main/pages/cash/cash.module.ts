import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {CashRoutingModule} from './cash-routing.module';
import {CashComponent} from './cash.component';

@NgModule({
  declarations: [
    CashComponent
  ],
  imports: [
    CommonModule,
    CashRoutingModule,
  ],
})
export class CashModule {
}
