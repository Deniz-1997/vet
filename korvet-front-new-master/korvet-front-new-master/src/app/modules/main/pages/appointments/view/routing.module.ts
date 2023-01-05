import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ViewComponent} from './view.component';

const routes: Routes = [
  {
    path: '',
    component: ViewComponent
  },
  {
    path: 'edit',
    loadChildren: () => import('../edit/edit.module').then(m => m.EditModule),
    data: {breadcrumb: 'Редактирование приема'}
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class RoutingModule {
}
