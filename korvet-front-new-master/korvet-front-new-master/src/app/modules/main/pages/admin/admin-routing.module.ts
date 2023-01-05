import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {RolesGuard} from 'src/app/api/api-menu/roles.guard';
import {AuthGuard} from 'src/app/api/auth/auth.guard';
import {AdminComponent} from './admin.component';

const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard, RolesGuard],
    component: AdminComponent,
    children: [
      {
        path: '',
        loadChildren: () => import('./catalog/main.module').then(m => m.MainModule)
      },
      {
        path: 'references',
        canActivate: [RolesGuard],
        data: {title: 'Справочники', 'breadcrumb': 'Справочники'},
        loadChildren: () => import('./references/main.module').then(m => m.MainModule)
      },
      {
        path: 'user',
        canActivate: [RolesGuard],
        data: {title: 'Пользователи', 'breadcrumb': 'Пользователи'},
        loadChildren: () => import('./user/main.module').then(m => m.MainModule),
      },
      {
        path: 'group',
        canActivate: [RolesGuard],
        data: {title: 'Группы пользователей', 'breadcrumb': 'Группы пользователей'},
        loadChildren: () => import('./group/main.module').then(m => m.MainModule),
      },
      {
        path: 'role',
        canActivate: [RolesGuard],
        data: {title: 'Роли доступа', 'breadcrumb': 'Роли доступа'},
        loadChildren: () => import('./role/main.module').then(m => m.MainModule),
      },
      {
        path: 'settings',
        canActivate: [RolesGuard],
        data: {title: 'Настройки', 'breadcrumb': 'Настройки'},
        loadChildren: () => import('./settings/main.module').then(m => m.MainModule),
      },
      {
        path: 'appointment-template',
        canActivate: [RolesGuard],
        data: {title: 'Шаблоны услуг и товаров', 'breadcrumb': 'Шаблоны услуг и товаров'},
        loadChildren: () => import('./appointment-template/main.module').then(m => m.MainModule)
      },
      {
        path: 'appointments/user-schedule',
        canActivate: [RolesGuard],
        data: {title: 'График работы врачей', 'breadcrumb': 'График работы врачей'},
        loadChildren: () => import('./appointments-user-schedule/main.module').then(m => m.MainModule)
      }, {
        path: 'notifications',
        canActivate: [RolesGuard],
        data: {title: 'Оповещения', 'breadcrumb': 'Оповещения'},
        loadChildren: () => import('./notifications/main.module').then(m => m.MainModule)
      },
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AdminRoutingModule {
}
