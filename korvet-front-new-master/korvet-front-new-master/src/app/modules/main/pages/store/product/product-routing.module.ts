import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ListComponent as ProductListComponent} from './list/list.component';
import {EditComponent as ProductEditComponent} from './edit/edit.component';
import {AuthGuard} from 'src/app/api/auth/auth.guard';

const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        data: {title: ''},
        component: ProductListComponent
      },
      {
        path: ':id',
        component: ProductEditComponent,
        data: {breadcrumb: 'Редактировать номенклатуру'}
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ProductRoutingModule {
}
