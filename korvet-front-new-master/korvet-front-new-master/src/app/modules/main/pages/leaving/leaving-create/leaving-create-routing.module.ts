import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {LeavingCreateComponent} from './leaving-create.component';

const routes: Routes = [
  {
    path: '',
    component: LeavingCreateComponent
  },
  {
    path: 'leaving-create',
    loadChildren: () => import('../leaving-create/leaving-create.module').then(m => m.LeavingCreateModule),
    data: {breadcrumb: 'Создание выезда'}
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class LeavingCreateRoutingModule { }
