import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {AuthGuard} from 'src/app/api/auth/auth.guard';
import {ListComponent} from './list/list.component';
import {ViewComponent} from './view/view.component';

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
        path: ':id',
        component: ViewComponent,
        data: {breadcrumb: 'Просмотр истории'}
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class FtpHistoryRoutingModule {
}
