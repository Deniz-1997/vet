import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ViewComponent} from './view.component';

const routes: Routes = [
  {
    path: '',
    children: [
      {
        path: '',
        // canActivate: [RolesGuard],
        data: {title: 'Вакцины', 'breadcrumb': 'Справочники'},
        component: ViewComponent,
      },
      {
        path: 'vaccine-common',
        // canActivate: [RolesGuard],
        data: {title: 'Вакцины (общий)', 'breadcrumb': 'Вакцины (общий)'},
        loadChildren: () => import('../vaccine-common/vaccine-common.module').then(m => m.VaccineCommonModule)
      },
      {
        path: 'vaccine-manufacturer',
        // canActivate: [RolesGuard],
        data: {title: 'Производители', 'breadcrumb': 'Производители'},
        loadChildren: () => import('../vaccine-manufacturer/vaccine-manufacturer.module').then(m => m.VaccineManufacturerModule)
      },
      {
        path: 'vaccine-series',
        // canActivate: [RolesGuard],
        data: {title: 'Серии', 'breadcrumb': 'Серии'},
        loadChildren: () => import('../vaccine-series/vaccine-series.module').then(m => m.VaccineSeriesModule)
      },
      {
        path: 'vaccine-disease',
        // canActivate: [RolesGuard],
        data: {title: 'Заболевания', 'breadcrumb': 'Заболевания'},
        loadChildren: () => import('../vaccine-disease/vaccine-disease.module').then(m => m.VaccineDiseaseModule)
      },
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class RoutingModule {
}
