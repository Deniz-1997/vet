import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {LeavingComponent} from './leaving.component';

const routes: Routes = [{
  path: '',
  component: LeavingComponent,
  children: [
    {
      path: '',
      loadChildren: () => import('./leaving-list/leaving-list.module').then(m => m.LeavingListModule),
    },
    {
      path: 'leaving',
      children: [
        {
          path: '',
          pathMatch: 'full',
          redirectTo: '/'
        },
        {
          path: 'leaving-create',
          loadChildren: () => import('./leaving-create/leaving-create.module').then(m => m.LeavingCreateModule),
          data: {breadcrumb: 'Создание выезда'},
        },
        {
          path: 'leaving-schedule',
          loadChildren: () => import('./leaving-schedule/leaving-schedule.module').then(m => m.LeavingScheduleModule),
          data: {breadcrumb: 'График работ'},
        },
        {
          path: 'leaving-list',
          loadChildren: () => import('./leaving-list/leaving-list.module').then(m => m.LeavingListModule),
          data: {breadcrumb: 'Все выезды'}
        },
        {
          path: ':id',
          loadChildren: () => import('./leaving-view/leaving-view.module').then(m => m.LeavingViewModule),
          data: {breadcrumb: 'Выезд'}
        },
      ],
    },
  ]
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class LeavingRoutingModule { }
