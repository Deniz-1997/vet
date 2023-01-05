import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {AdminComponent} from './admin.component';
import {RolesGuard} from '../../../../api/api-menu/roles.guard';
import {AuthGuard} from '../../../../api/auth/auth.guard';
import {MaintenanceComponent} from './maintenance/maitenence.component';

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
      /* {
        path: 'references',
        canActivate: [RolesGuard],
        data: {title: 'Справочники', 'breadcrumb': 'Справочники'},
        loadChildren: () => import('./references/main.module').then(m => m.VaccineManufacturerRoutingModule)
      }, */
      {
        path: 'user',
        canActivate: [RolesGuard],
        data: {title: 'Пользователи', breadcrumb: 'Пользователи'},
        loadChildren: () => import('./user/main.module').then(m => m.MainModule),
      },
      {
        path: 'group',
        canActivate: [RolesGuard],
        data: {title: 'Группы пользователей', breadcrumb: 'Группы пользователей'},
        loadChildren: () => import('./group/main.module').then(m => m.MainModule),
      },
      {
        path: 'role',
        canActivate: [RolesGuard],
        data: {title: 'Роли доступа', breadcrumb: 'Роли доступа'},
        loadChildren: () => import('./role/main.module').then(m => m.MainModule),
      },
      {
        path: 'settings',
        canActivate: [RolesGuard],
        data: {title: 'Настройки', breadcrumb: 'Настройки'},
        loadChildren: () => import('./settings/main.module').then(m => m.MainModule),
      },
      {
        path: 'notifications',
        canActivate: [RolesGuard],
        data: {title: 'Оповещения', breadcrumb: 'Оповещения'},
        loadChildren: () => import('./notifications/main.module').then(m => m.MainModule)
      },
      {
        path: 'action',
        canActivate: [RolesGuard],
        data: {title: 'Действия', breadcrumb: 'Действия'},
        loadChildren: () => import('./action/main.module').then(m => m.MainModule)
      },
      {
        path: 'action-group',
        data: {title: 'Группы действий', breadcrumb: 'Группы действий'},
        loadChildren: () => import('./action-group/main.module').then(m => m.MainModule)
      },
      {
        path: 'maintenance',
        data: {title: 'Обслуживание', breadcrumb: 'Обслуживание'},
        component: MaintenanceComponent
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
