import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {AuthGuard} from 'src/app/api/auth/auth.guard';
import {EditComponent as ProductTransferEditComponent} from './edit/edit.component';
import {ListComponent as ProductTransferListComponent} from './list/list.component';
import {ViewComponent as ProductTransferViewComponent} from './view/view.component';

const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        data: {title: ''},
        component: ProductTransferListComponent
      },
      {
        path: 'create',
        component: ProductTransferEditComponent,
        data: {breadcrumb: 'Создание'},
      },
      {
        path: ':id/edit',
        component: ProductTransferEditComponent,
        data: {breadcrumb: 'Редактировать перемещение товаров'}
      },
      {
        path: ':id',
        component: ProductTransferViewComponent,
        data: {breadcrumb: 'Просмотр товара'}
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class TransferRoutingModule {
}
