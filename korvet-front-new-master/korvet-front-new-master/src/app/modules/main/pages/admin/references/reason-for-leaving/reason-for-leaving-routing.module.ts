import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {ReasonForLeavingListComponent} from './reason-for-leaving-list/reason-for-leaving-list.component';
import {ReasonForLeavingEditComponent} from './reason-for-leaving-edit/reason-for-leaving-edit.component';
import {AuthGuard} from 'src/app/api/auth/auth.guard';

const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        data: {title: ''},
        component: ReasonForLeavingListComponent
      },
      {
        path: ':id',
        component: ReasonForLeavingEditComponent,
        data: {breadcrumb: 'Редактировать причины выезда'}
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ReasonForLeavingRoutingModule { }
