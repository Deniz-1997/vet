import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {AuthGuard} from 'src/app/api/auth/auth.guard';

const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        loadChildren: () => import('./list/list.module').then(m => m.ListModule)
      },
      {
        path: 'shift',
        data: {title: 'Отчет за смену', 'breadcrumb': 'Отчет за смену'},
        loadChildren: () => import('./shift/shift.module').then(m => m.ShiftModule)
      },
      {
        path: 'revenue-report',
        data: {title: 'Отчет по выручке', 'breadcrumb': 'Отчет по выручке'},
        loadChildren: () => import('./revenue/revenue.module').then(m => m.RevenueModule)
      },
      {
        path: 'warehouse-statement',
        data: {title: 'Ведомость по товарам на складах', 'breadcrumb': 'Ведомость по товарам на складах'},
        loadChildren: () => import('./warehouse-statement/warehouse-statement.module').then(m => m.WarehouseStatementModule)
      },
      {
        path: 'culling-report',
        data: {title: 'Отчет по отлову животных', 'breadcrumb': 'Отчет по отлову животных'},
        loadChildren: () => import('./culling/culling.module').then(m => m.CullingModule)
      },
      {
        path: 'total-revenue-report',
        data: {title: 'Расшифровка по выручке', 'breadcrumb': 'Расшифровка по выручке'},
        loadChildren: () => import('./total-revenue/total-revenue.module').then(m => m.TotalRevenueModule)
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ReportsRoutingModule {
}
