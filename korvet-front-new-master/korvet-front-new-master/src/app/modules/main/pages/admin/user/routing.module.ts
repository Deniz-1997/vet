import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ListComponent} from './list/list.component';
import {EditComponent} from './edit/edit.component';
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
        path: 'create',
        component: EditComponent,
        data: {breadcrumb: 'Создать пользователя'}
      },
      {
        path: ':id',
        loadChildren: () => import('./view/view.module').then(m => m.ViewModule),
        data: {breadcrumb: 'Профиль пользователя', title: 'Профиль пользователя'},
      },
      {
        path: ':id',
        data: {breadcrumb: 'Профиль пользователя', title: 'Профиль пользователя'},
        children: [
          {
            path: 'edit',
            component: EditComponent,
            data: {breadcrumb: 'Редактировать пользователя'}
          }
        ]
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
