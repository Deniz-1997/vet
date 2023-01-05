import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RevenueRoutingModule} from './revenue-routing.module';
import {RevenueComponent} from './revenue.component';
import {SharedModule} from '../../../../shared/shared.module';

@NgModule({
  declarations: [RevenueComponent],
  imports: [
    CommonModule,
    SharedModule,
    RevenueRoutingModule,
  ]
})
export class RevenueModule {
}
