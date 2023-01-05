import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {VaccinationOldComponent} from './vaccination-old.component';
import {ViewOldComponent} from './view/view-old.component';


const routes: Routes = [{
  path: '',
  // canActivate: [AuthGuard],
  children: [
    {
      path: '',
      data: {title: ''},
      component: VaccinationOldComponent,
    },
      {
        path: ':id',
        component: ViewOldComponent,
        data: {breadcrumb: 'Просмотр отчета по импорту'}
      }
    ]
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class VaccinationRoutingModule {
}
