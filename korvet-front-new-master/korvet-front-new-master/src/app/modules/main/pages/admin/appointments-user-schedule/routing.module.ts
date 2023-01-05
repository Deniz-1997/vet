import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ListComponent} from './list/list.component';
import {EditComponent} from './edit/edit.component';
import {PeriodComponent} from './period/period.component';
import {AuthGuard} from 'src/app/api/auth/auth.guard';

const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        data: {title: ''},
        component: ListComponent
      },
      {
        path: 'period-create',
        component: PeriodComponent,
        data: {breadcrumb: 'Добавить за период'}
      },
      {
        path: ':id',
        component: EditComponent,
        data: {breadcrumb: 'Редактировать график работы врачей'}
      },
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class RoutingModule {
}
