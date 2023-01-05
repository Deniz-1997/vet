import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {WarehouseStatementRoutingModule} from './warehouse-statement-routing.module';
import {WarehouseStatementComponent} from './warehouse-statement.component';
import {SharedModule} from '../../../../shared/shared.module';

@NgModule({
  declarations: [WarehouseStatementComponent],
  imports: [
    CommonModule,
    SharedModule,
    WarehouseStatementRoutingModule
  ]
})
export class WarehouseStatementModule {
}
