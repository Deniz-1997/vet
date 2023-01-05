import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ListComponent as ProductExpenseListComponent} from './list/list.component';
import {EditComponent as ProductExpenseEditComponent} from './edit/edit.component';
import {ViewComponent as ProductExpenseViewComponent} from './view/view.component';
import {AuthGuard} from 'src/app/api/auth/auth.guard';

const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        data: {title: ''},
        component: ProductExpenseListComponent
      },
      {
        path: 'create',
        component: ProductExpenseEditComponent,
        data: {breadcrumb: 'Создание'},
      },
      {
        path: ':id/edit',
        component: ProductExpenseEditComponent,
        data: {breadcrumb: 'Редактировать поступление товаров'}
      },
      {
        path: ':id',
        component: ProductExpenseViewComponent,
        data: {breadcrumb: 'Просмотр расхода товаров'}
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})

export class ExpenseRoutingModule {
}
