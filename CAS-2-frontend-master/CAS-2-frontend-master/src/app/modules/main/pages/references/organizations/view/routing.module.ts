import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ViewComponent} from './view.component';
import {AdminComponent} from '../../../admin/admin.component';

const routes: Routes = [
  {
    path: '',
    children: [
      {
        path: '',
        // canActivate: [RolesGuard],
        data: {title: 'Организации', 'breadcrumb': 'Справочники'},
        component: ViewComponent,
      },
      {
        path: 'station',
        // canActivate: [RolesGuard],
        data: {title: 'Станции', 'breadcrumb': 'Станции'},
        loadChildren: () => import('../stations/main.module').then(m => m.MainModule)
      },
      {
        path: 'supervised-objects',
        // canActivate: [RolesGuard],
        data: {title: 'Поднадзорные объекты', 'breadcrumb': 'Поднадзорные объекты'},
        loadChildren: () => import('../supervised-object/supervised-object.module').then(m => m.SupervisedObjectModule)
      },
      {
        path: 'business-entity',
        // canActivate: [RolesGuard],
        data: {title: 'Хозяйствующие субъекты', 'breadcrumb': 'Хозяйствующие субъекты'},
        loadChildren: () => import('../business-entity/business-entity.module').then(m => m.BusinessEntityModule)
      },
      {
        path: 'subdivision',
        // canActivate: [RolesGuard],
        data: {title: 'Подразделения (используется при импорте вакцинаций)', 'breadcrumb': 'Подразделения (используется при импорте вакцинаций)'},
        loadChildren: () => import('../subdivisions/subdivision.module').then(m => m.SubdivisionModule)
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
