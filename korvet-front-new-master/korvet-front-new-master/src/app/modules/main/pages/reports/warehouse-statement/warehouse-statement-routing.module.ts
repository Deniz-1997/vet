import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {WarehouseStatementComponent} from './warehouse-statement.component';

const routes: Routes = [
  {
    path: '',
    data: {'breadcrumb': 'Ведомость по товарам на складах'},
    component: WarehouseStatementComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class WarehouseStatementRoutingModule {
}
