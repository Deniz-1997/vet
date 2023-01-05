import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {TotalRevenueComponent} from './total-revenue.component';

const routes: Routes = [
  {
    path: '',
    data: {'breadcrumb': 'Расшифровка по выручке'},
    component: TotalRevenueComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class TotalRevenueRoutingModule {
}
