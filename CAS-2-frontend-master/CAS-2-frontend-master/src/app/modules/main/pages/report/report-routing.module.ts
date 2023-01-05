import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ReportComponent} from './report.component';
import {RolesGuard} from '../../../../api/api-menu/roles.guard';
import {Vet3Module} from './reports/3-vet/3-vet.module';


const routes: Routes = [
  {
    path: '',
    canActivate: [RolesGuard],
    component: ReportComponent,
    children: [
      {
        path: '',
        loadChildren: () => import('./catalog/main.module').then(m => m.MainModule)
      },
      {
        path: 'vaccinations',
        data: {title: '', breadcrumb: 'Импорт вакцинаций'},
        loadChildren: () => import('./vaccination/vaccination.module').then(m => m.VaccinationModule),
      },
      {
        path: 'reproduction',
        data: {title: '', breadcrumb: 'Воcпроизводство'},
        loadChildren: () => import('./reports/reproduction/reproduction.module').then(m => m.ReproductionModule),
      },
      {
        path: 'livestock-of-animals',
        data: {title: '', breadcrumb: 'Поголовье животных'},
        loadChildren: () => import('./reports/livestock-of-animals/livestock-of-animals.module').then(m => m.LivestockOfAnimalsModule),
      },
      {
        path: '3-vet',
        data: {title: '', breadcrumb: '3-Вет'},
        loadChildren: () => import('./reports/3-vet/3-vet.module').then(m => m.Vet3Module),
      },
      {
        path: '1-vet-a',
        data: {title: '', breadcrumb: '1-Вет А'},
        loadChildren: () => import('./reports/1-vet-a/1-vet-a.module').then(m => m.Vet1AModule),
      },
      {
        path: 'disinfectants',
        data: {title: '', breadcrumb: 'Дез. средства'},
        loadChildren: () => import('./reports/disinfectants/disinfectants.module').then(m => m.DisinfectantsModule),
      },
      {
        path: '2-vet',
        data: {title: '', breadcrumb: '2-Вет'},
        loadChildren: () => import('./reports/2-vet/2-vet.module').then(m => m.Vet2Module),
      },
      {
        path: '1-vet-g',
        data: {title: '', breadcrumb: '1-Вет Г'},
        loadChildren: () => import('./reports/1-vet-g/1-vet-g.module').then(m => m.Vet1GModule),
      },
      {
        path: 'leukemia',
        data: {title: '', breadcrumb: 'Лейкоз'},
        loadChildren: () => import('./reports/leukemia/leukemia.module').then(m => m.LeukemiaModule),
      },
      {
        path: 'pigs-move',
        data: {title: '', breadcrumb: 'Движение свиней'},
        loadChildren: () => import('./reports/pigs-move/pigs-move.module').then(m => m.PigsMoveModule),
      },
    ]
  },
];


@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ReportRoutingModule {
}
