import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {DiseaseListComponent} from './disease-list/disease-list.component';
import {DiseaseEditComponent} from './disease-edit/disease-edit.component';
import {AuthGuard} from 'src/app/api/auth/auth.guard';


const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        data: {title: ''},
        component: DiseaseListComponent
      },
      {
        path: ':id',
        component: DiseaseEditComponent,
        data: {breadcrumb: 'Редактировать заболевание'}
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class DiseaseRoutingModule { }
