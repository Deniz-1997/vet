import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {RevenueComponent} from './revenue.component';

const routes: Routes = [
  {
    path: '',
    data: {'breadcrumb': 'Отчет по выручке'},
    component: RevenueComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class RevenueRoutingModule {
}
