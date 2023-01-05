import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ListComponent} from './list/list.component';
import {EditComponent} from './edit/edit.component';
import {AuthGuard} from 'src/app/api/auth/auth.guard';


const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        data: {title: '', breadcrumb: 'Шаблоны услуг и товаров'},
        component: ListComponent
      },
      {
        path: ':id',
        component: EditComponent,
        data: {breadcrumb: 'Редактировать шаблон приема'}
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class RoutingModule {
}