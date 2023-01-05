import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ListComponent} from './list.component';

const routes: Routes = [
  {
    path: '',
    data: {title: 'Склад', 'breadcrumb': 'Склад'},
    component: ListComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class RoutingModule {
}
