import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ListComponent as ProductReceiptListComponent} from './list/list.component';
import {EditComponent as ProductReceiptEditComponent} from './edit/edit.component';
import {ViewComponent as ProductReceiptViewComponent} from './view/view.component';
import {AuthGuard} from 'src/app/api/auth/auth.guard';

const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        data: {title: ''},
        component: ProductReceiptListComponent
      },
      {
        path: 'create',
        component: ProductReceiptEditComponent,
        data: {breadcrumb: 'Создание'},
      },
      {
        path: ':id/edit',
        component: ProductReceiptEditComponent,
        data: {breadcrumb: 'Редактировать поступление товаров'}
      },
      {
        path: ':id',
        component: ProductReceiptViewComponent,
        data: {breadcrumb: 'Просмотр товара'}
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ReceiptRoutingModule {
}
