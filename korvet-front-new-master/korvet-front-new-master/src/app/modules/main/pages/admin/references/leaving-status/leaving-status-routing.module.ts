import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {LeavingStatusListComponent} from './leaving-status-list/leaving-status-list.component';
import {LeavingStatusEditComponent} from './leaving-status-edit/leaving-status-edit.component';
import {AuthGuard} from 'src/app/api/auth/auth.guard';


const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        data: {title: ''},
        component: LeavingStatusListComponent
      },
      {
        path: ':id',
        component: LeavingStatusEditComponent,
        data: {breadcrumb: 'Редактировать статусы выезда'}
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class LeavingStatusRoutingModule { }
