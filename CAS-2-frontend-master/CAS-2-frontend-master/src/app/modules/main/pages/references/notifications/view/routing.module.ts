import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ViewComponent} from './view.component';
import {AdminComponent} from '../../../admin/admin.component';

const routes: Routes = [
  {
    path: '',
    children: [
      {
        path: '',
        // canActivate: [RolesGuard],
        data: {title: 'Оповещения', 'breadcrumb': 'Справочники'},
        component: ViewComponent,
      },
      {
        path: 'notifications-channel',
        // canActivate: [RolesGuard],
        data: {title: 'Каналы оповещений', 'breadcrumb': 'Каналы оповещений'},
        loadChildren: () => import('../channel/main.module').then(m => m.MainModule)
      },
      {
        path: 'notifications-type',
        // canActivate: [RolesGuard],
        data: {title: 'Типы оповещений', 'breadcrumb': 'Типы оповещений'},
        loadChildren: () => import('../type/main.module').then(m => m.MainModule)
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
