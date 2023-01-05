import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {BalanceRoutingModule} from './balance-routing.module';
import {ListComponent} from './list/list.component';
import {SharedModule} from '../../../../shared/shared.module';
import {EditComponent} from './edit/edit.component';

@NgModule({
  declarations: [ListComponent, EditComponent],
  imports: [
    CommonModule,
    SharedModule,
    BalanceRoutingModule
  ]
})
export class BalanceModule {
}
