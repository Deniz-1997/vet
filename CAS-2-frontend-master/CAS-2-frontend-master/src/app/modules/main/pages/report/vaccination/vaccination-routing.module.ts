import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {VaccinationComponent} from './vaccination.component';
import {ViewComponent} from './view/view.component';


const routes: Routes = [{
  path: '',
  // canActivate: [AuthGuard],
  children: [
    {
      path: '',
      data: {title: ''},
      component: VaccinationComponent,
    },
    {
      path: ':id',
      component: ViewComponent,
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
