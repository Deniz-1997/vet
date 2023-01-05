import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {SharedModule} from '../../../../shared/shared.module';
import {TotalRevenueComponent} from './total-revenue.component';
import {TotalRevenueRoutingModule} from './total-revenue-routing.module';

@NgModule({
  declarations: [TotalRevenueComponent],
  imports: [
    CommonModule,
    SharedModule,
    TotalRevenueRoutingModule
  ]
})
export class TotalRevenueModule {
}
