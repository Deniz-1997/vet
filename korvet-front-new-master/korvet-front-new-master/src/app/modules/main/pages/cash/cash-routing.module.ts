import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {RolesGuard} from 'src/app/api/api-menu/roles.guard';
import {AuthGuard} from 'src/app/api/auth/auth.guard';
import {CashComponent} from './cash.component';

const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard, RolesGuard],
    component: CashComponent,
    children: [
      {
        path: '',
        loadChildren: () => import('./list/list.module').then(m => m.ListModule)
      },
      {
        path: 'organization',
        canActivate: [RolesGuard],
        data: {title: 'Организации', 'breadcrumb': 'Организации'},
        loadChildren: () => import('./organization/main.module').then(m => m.MainModule)
      },
      {
        path: 'unit',
        canActivate: [RolesGuard],
        data: {title: 'Клиники', 'breadcrumb': 'Клиники'},
        loadChildren: () => import('./unit/main.module').then(m => m.MainModule)
      },
      {
        path: 'cash-register',
        canActivate: [RolesGuard],
        data: {title: 'ККМ', 'breadcrumb': 'ККМ'},
        loadChildren: () => import('./cash-register/main.module').then(m => m.MainModule)
      },
      {
        path: 'cash-register-server',
        canActivate: [RolesGuard],
        data: {title: 'ККМ-серверы', 'breadcrumb': 'ККМ-серверы'},
        loadChildren: () => import('./cash-register-server/main.module').then(m => m.MainModule)
      },
      {
        path: 'cashier-schedule',
        data: {title: 'График работы кассиров', 'breadcrumb': 'График работы кассиров'},
        loadChildren: () => import('./cashier-schedule/main.module').then(m => m.MainModule)
      },
      {
        path: 'cash',
        canActivate: [RolesGuard],
        data: {title: 'Текущая смена', 'breadcrumb': 'Текущая смена'},
        loadChildren: () => import('./cash-view/main.module').then(m => m.MainModule)
      },
      {
        path: 'shift',
        canActivate: [RolesGuard],
        data: {title: 'Кассовые смены', 'breadcrumb': 'Кассовые смены'},
        loadChildren: () => import('./shift/main.module').then(m => m.MainModule)
      },
      {
        path: 'cash-receipt',
        canActivate: [RolesGuard],
        data: {title: 'Кассовые чеки', 'breadcrumb': 'Кассовые чеки'},
        loadChildren: () => import('./cash-receipt/receipt.module').then(m => m.ReceiptModule)
      },
      {
        path: 'cash-flow',
        canActivate: [RolesGuard],
        data: {title: 'Внесение / выплата', 'breadcrumb': 'Внесение / выплата'},
        loadChildren: () => import('./flow/flow.module').then(m => m.FlowModule)
      },
      {
        path: 'device-cashbox',
        canActivate: [RolesGuard],
        data: {title: 'Терминалы оплат', 'breadcrumb': 'Терминалы оплат'},
        loadChildren: () => import('./device-cashbox/main.module').then(m => m.MainModule)
      },

    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class CashRoutingModule {
}
