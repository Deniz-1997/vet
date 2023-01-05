import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {AuthGuard} from 'src/app/api/auth/auth.guard';
import {EditComponent} from './edit/edit.component';
import {ListComponent} from './list/list.component';


const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        data: {title: ''},
        component: ListComponent
      },
      {
        path: ':id',
        component: EditComponent,
        data: {breadcrumb: 'Редактировать тип материала'}
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
