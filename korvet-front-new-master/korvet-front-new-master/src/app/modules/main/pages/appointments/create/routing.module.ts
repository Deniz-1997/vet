import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {CreateComponent} from './create.component';

const routes: Routes = [
  {
    path: '',
    component: CreateComponent
  },
  {
    path: 'create',
    loadChildren: () => import('../create/create.module').then(m => m.CreateModule),
    data: {breadcrumb: 'Создание приема'}
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class RoutingModule {
}
