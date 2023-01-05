import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {AuthGuard} from 'src/app/api/auth/auth.guard';
import {ListComponent as ProductHistoryListComponent} from './list/list.component';
import {ViewComponent as ProductHistoryViewComponent} from './view/view.component';

const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        data: {title: ''},
        component: ProductHistoryListComponent
      },
      {
        path: ':id',
        component: ProductHistoryViewComponent,
        data: {breadcrumb: 'Просмотр движения остатков'}
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class HistoryRoutingModule {
}
