import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ListComponent as ProductInventoryListComponent} from './list/list.component';
import {EditComponent as ProductInventoryEditComponent} from './edit/edit.component';
import {ViewComponent as ProductInventoryViewComponent} from './view/view.component';
import {AuthGuard} from 'src/app/api/auth/auth.guard';

const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        data: {title: ''},
        component: ProductInventoryListComponent
      },
      {
        path: 'create',
        component: ProductInventoryEditComponent,
        data: {breadcrumb: 'Создание'}
      },
      {
        path: ':id/edit',
        component: ProductInventoryEditComponent,
        data: {breadcrumb: 'Редактировать инвентаризацию'}
      },
      {
        path: ':id',
        component: ProductInventoryViewComponent,
        data: {breadcrumb: 'Инвентаризация'}
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class InventoryRoutingModule {
}
