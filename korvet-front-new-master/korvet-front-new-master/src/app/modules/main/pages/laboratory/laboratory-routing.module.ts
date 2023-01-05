import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ProbeSamplingListComponent } from './probe-sampling/list/list.component';
import { ProbeSamplingComponent } from './probe-sampling/edit/edit.component';
import { ResearchDocumentListComponent } from './research/list/list.component';
import { ResearchDocumentComponent } from './research/edit/edit.component';
import {AuthGuard} from 'src/app/api/auth/auth.guard';
import {RolesGuard} from 'src/app/api/api-menu/roles.guard';

const routes: Routes = [{
  path: '',
  canActivate: [AuthGuard, RolesGuard],
  children: [
    {
      path: 'probe-sampling',
      component: ProbeSamplingListComponent,
      data: {breadcrumb: 'Список отборов проб'},
    },
    {
      path: 'probe-sampling',
      data: {breadcrumb: 'Список отборов проб'},
      children: [
        {
          path: 'create',
          component: ProbeSamplingComponent,
          data: {breadcrumb: 'Отбор проб'}
        },
        {
          path: ':id',
          component: ProbeSamplingComponent,
          data: {breadcrumb: 'Отбор проб'}
        },
      ]
    },
    {
      path: 'research-document',
      component: ResearchDocumentListComponent,
      data: {breadcrumb: 'Список исследований'},
    },
    {
      path: 'research-document',
      data: {breadcrumb: 'Список исследований'},
      children: [
        {
          path: ':id',
          component: ResearchDocumentComponent,
          data: {breadcrumb: 'Исследование'}
        },
      ]
    },
  ]
}];
@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class LaboratoryRoutingModule { }
