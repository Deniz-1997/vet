import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {HistoryComponent} from './history.component';

const routes: Routes = [
  {
    path: '',
    component: HistoryComponent,
  },
  {
    path: 'add',
    loadChildren: () => import('./form/form.module').then(m => m.FormModule),
    data: {breadcrumb: 'Добавить прием'},
  },
  {
    path: 'add/:id',
    loadChildren: () => import('./form/form.module').then(m => m.FormModule),
    data: {breadcrumb: 'Добавить прием'},
  },
  {
    path: 'add-leaving',
    loadChildren: () => import('../leaving/add-leaving/add-form.module').then(m => m.AddFormModule),
    data: {breadcrumb: 'Добавить прием'},
  },
  {
    path: 'add-leaving/:id',
    loadChildren: () => import('../leaving/add-leaving/add-form.module').then(m => m.AddFormModule),
    data: {breadcrumb: 'Добавить выезд'},
  },
  {
    path: ':appointmentId',
    loadChildren: () => import('./form/form.module').then(m => m.FormModule),
    data: {breadcrumb: 'Редактировать прием'},
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class HistoryRoutingModule {
}
