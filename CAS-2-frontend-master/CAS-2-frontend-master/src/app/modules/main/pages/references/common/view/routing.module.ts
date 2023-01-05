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
        data: {title: 'Общие', 'breadcrumb': 'Справочники'},
        component: ViewComponent,
      },
      {
        path: 'disinfectants',
        data: {title: 'Дез. средства', 'breadcrumb': 'Дез. средств'},
        loadChildren: () => import('../disinfectants/main.module').then(m => m.MainModule)
      },
      {
        path: 'common-countries',
        data: {title: 'Страны', 'breadcrumb': 'Страны'},
        loadChildren: () => import('../countries/main.module').then(m => m.MainModule)
      },
      {
        path: 'common-measurement-units',
        data: {title: 'Единицы измерения', 'breadcrumb': 'Единицы измерения'},
        loadChildren: () => import('../measurement-units/main.module').then(m => m.MainModule)
      },
      {
        path: 'circle',
        data: {title: 'Область', 'breadcrumb': 'Область'},
        loadChildren: () => import('../circle/main.module').then(m => m.MainModule)
      },
      {
        path: 'paths',
        data: {title: 'Место', 'breadcrumb': 'Место'},
        loadChildren: () => import('../paths/main.module').then(m => m.MainModule)
      },
      {
        path: 'location',
        data: {title: 'Местоположение', 'breadcrumb': 'Местоположение'},
        loadChildren: () => import('../location/main.module').then(m => m.MainModule)
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
