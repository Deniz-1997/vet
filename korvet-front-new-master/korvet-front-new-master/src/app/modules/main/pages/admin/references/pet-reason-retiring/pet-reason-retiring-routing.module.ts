import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {PetReasonRetiringListComponent} from './pet-reason-retiring-list/pet-reason-retiring-list.component';
import {PetReasonRetiringEditComponent} from './pet-reason-retiring-edit/pet-reason-retiring-edit.component';
import {AuthGuard} from 'src/app/api/auth/auth.guard';

const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        data: {title: ''},
        component: PetReasonRetiringListComponent
      },
      {
        path: ':id',
        component: PetReasonRetiringEditComponent,
        data: {breadcrumb: 'Редактировать причину выбытия'}
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})

export class PetReasonRetiringRoutingModule { }
