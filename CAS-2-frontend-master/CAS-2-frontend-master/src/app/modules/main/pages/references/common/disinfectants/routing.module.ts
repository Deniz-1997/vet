import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ListComponent} from './list/list.component';
import {EditComponent} from './edit/edit.component';
import {AuthGuard} from '../../../../../../api/auth/auth.guard';

const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        data: {title: '', breadcrumb: 'Дез. средства'},
        component: ListComponent
      },
      {
        path: ':id',
        component: EditComponent,
        data: {
          titleName: 'Дез. средства'
        }
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
