import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {AdminComponent} from '../admin/admin.component';
import {RolesGuard} from '../../../../api/api-menu/roles.guard';
import {AuthGuard} from '../../../../api/auth/auth.guard';

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
        path: 'pet',
        // canActivate: [RolesGuard],
        data: {title: 'Животные', 'breadcrumb': 'Животные'},
        loadChildren: () => import('./pets/view/view.module').then(m => m.ViewModule),
      },
      {
        path: 'icon',
        // canActivate: [RolesGuard],
        data: {title: 'Иконки', 'breadcrumb': 'Иконки'},
        loadChildren: () => import('./icon/main.module').then(m => m.MainModule)
      },
      {
        path: 'common',
        data: {title: 'Общие', 'breadcrumb': 'Общие'},
        loadChildren: () => import('./common/view/view.module').then(m => m.ViewModule)
      },
      {
        path: 'notifications',
        // canActivate: [RolesGuard],
        data: {title: 'Оповещения', 'breadcrumb': 'Оповещения'},
        loadChildren: () => import('./notifications/view/view.module').then(m => m.ViewModule),
      },
      {
        path: 'organization',
        // canActivate: [RolesGuard],
        data: {title: 'Организации', 'breadcrumb': 'Организации'},
        loadChildren: () => import('./organizations/view/view.module').then(m => m.ViewModule)
      },
      {
        path: 'vaccine',
        // canActivate: [RolesGuard],
        data: {title: 'Вакцины', 'breadcrumb': 'Вакцины'},
        loadChildren: () => import('./vaccine/view/view.module').then(m => m.ViewModule)
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
