import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ListComponent} from './list/list.component';
import {EditComponent} from './edit/edit.component';
import {ViewComponent} from './view/view.component';
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
        path: ':id/edit',
        component: EditComponent,
        data: {breadcrumb: 'Редактировать кассовый чек'}
      },
      {
        path: 'create',
        component: EditComponent,
        data: {breadcrumb: 'Редактировать кассовый чек'}
      },
      {
        path: ':id',
        component: ViewComponent,
        data: {breadcrumb: 'Кассовый чек'}
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class RoutingModule {
}
