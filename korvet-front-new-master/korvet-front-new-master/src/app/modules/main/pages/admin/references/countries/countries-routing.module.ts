import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {CountriesListComponent} from './countries-list/countries-list.component';
import {CountriesEditComponent} from './countries-edit/countries-edit.component';
import {AuthGuard} from 'src/app/api/auth/auth.guard';


const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        data: {title: ''},
        component: CountriesListComponent
      },
      {
        path: ':id',
        component: CountriesEditComponent,
        data: {breadcrumb: 'Редактировать страны'}
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class CountriesRoutingModule { }
