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
        data: {title: 'Животные', 'breadcrumb': 'Справочники'},
        component: ViewComponent,
      },
      {
        path: 'pet-common',
        // canActivate: [RolesGuard],
        data: {title: 'Животные (общий)', 'breadcrumb': 'Животные (общий)'},
        loadChildren: () => import('../common/main.module').then(m => m.MainModule),
      },
      {
        path: 'pet-breed',
        // canActivate: [RolesGuard],
        data: {title: 'Породы животных', 'breadcrumb': 'Породы животных'},
        loadChildren: () => import('../breed/main.module').then(m => m.MainModule),
      },
      {
        path: 'pet-kind',
        // canActivate: [RolesGuard],
        data: {title: 'Виды животных', 'breadcrumb': 'Виды животных'},
        loadChildren: () => import('../kind/main.module').then(m => m.MainModule),
      },
      {
        path: 'pet-colour',
        // canActivate: [RolesGuard],
        data: {title: 'Масти животных', 'breadcrumb': 'Масти животных'},
        loadChildren: () => import('../colour/main.module').then(m => m.MainModule),
      },
      {
        path: 'pet-living',
        // canActivate: [RolesGuard],
        data: {title: 'Место жительства', 'breadcrumb': 'Место жительства'},
        loadChildren: () => import('../living-places/main.module').then(m => m.MainModule)
      },
      {
        path: 'pet-stamp',
        // canActivate: [RolesGuard],
        data: {title: 'Виды меток животных', 'breadcrumb': 'Виды меток животных'},
        loadChildren: () => import('../stamps/main.module').then(m => m.MainModule)
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class RoutingModule {
}
