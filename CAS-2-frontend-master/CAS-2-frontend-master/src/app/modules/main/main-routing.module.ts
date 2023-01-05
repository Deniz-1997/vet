import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {MainComponent} from './components/main/main.component';
import {AuthGuard} from '../../api/auth/auth.guard';

const routes: Routes = [{
  path: '',
  component: MainComponent,
  canActivate: [AuthGuard],
  data: {breadcrumb: 'Главная'},
  children: [
    {
      path: '',
      loadChildren: () => import('./pages/default-page/default-page.module').then(m => m.DefaultPageModule)
    },
    {
      path: 'admin',
      loadChildren: () => import('./pages/admin/admin.module').then(m => m.AdminModule),
      data: {breadcrumb: 'Администрирование', pageTitle: 'Администрирование', title: 'Администрирование'}
    },
    {
      path: 'reports',
      loadChildren: () => import('./pages/report/report-main.module').then(m => m.ReportMainModule),
      data: {breadcrumb: 'Отчеты', pageTitle: '', title: ''}
    },
    {
      path: 'reference',
      loadChildren: () => import('./pages/references/main.module').then(m => m.MainModule),
      data: {breadcrumb: 'Справочники', pageTitle: 'Справочники', title: 'Справочники'}
    },
    {
      path: 'print-reports',
      loadChildren: () => import('./pages/print-reports/print-reports.module').then(m => m.PrintReportsModule),
      data: {breadcrumb: 'Печатные отчеты', pageTitle: 'Печатные отчеты', title: 'Печатные отчеты'}
    },
  ],
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class MainRoutingModule {
}
