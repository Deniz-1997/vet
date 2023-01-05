import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {LeavingViewComponent} from './leaving-view.component';

const routes: Routes = [
  {
    path: '',
    component: LeavingViewComponent
  },
  {
    path: 'leaving-edit',
    loadChildren: () => import('../leaving-edit/leaving-edit.module').then(m => m.LeavingEditModule),
    data: {breadcrumb: 'Редактирование выезда'}
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class LeavingRoutingModule {
}
