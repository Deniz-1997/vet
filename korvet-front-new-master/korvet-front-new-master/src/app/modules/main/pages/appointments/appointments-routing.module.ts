import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {AppointmentsComponent} from './appointments.component';

const routes: Routes = [{
  path: '',
  component: AppointmentsComponent,
  children: [
    {
      path: '',
      loadChildren: () => import('./list/list.module').then(m => m.ListModule),
    },
    {
      path: 'appointments',
      children: [
        {
          path: '',
          pathMatch: 'full',
          redirectTo: '/'
        },
        {
          path: 'create',
          loadChildren: () => import('./create/create.module').then(m => m.CreateModule),
          data: {breadcrumb: 'Создание приема'},
        },
        {
          path: 'schedule',
          loadChildren: () => import('./schedule/schedule.module').then(m => m.ScheduleModule),
          data: {breadcrumb: 'График работ'},
        },
        {
          path: ':id',
          loadChildren: () => import('./view/view.module').then(m => m.ViewModule),
          data: {breadcrumb: 'Прием'}
        },
      ],
    },
  ]
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AppointmentsRoutingModule {
}
