import { NgModule } from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {AuthGuard} from '../../../../api/auth/auth.guard';
import {RolesGuard} from '../../../../api/api-menu/roles.guard';
import {UsersBusinessEntityComponent} from './users-business-entity/users-business-entity.component';
import {ReportsCountComponent} from './reports-count/reports-count.component';


const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard, RolesGuard],
    children: [
      {
        path: 'users-business-entity',
        data: {title: ' ', 'breadcrumb': 'Пользователи хозяйствующих субъектов'},
        component: UsersBusinessEntityComponent,
      },
      {
        path: 'reports-count',
        data: {title: ' ', 'breadcrumb': 'Количество сданных отчетов'},
        component: ReportsCountComponent,
      },
    ]
  }
];


@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PrintReportsRoutingModule {

}
