import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {MeasurementUnitsListComponent} from './measurement-units-list/measurement-units-list.component';
import {MeasurementUnitsEditComponent} from './measurement-units-edit/measurement-units-edit.component';
import {AuthGuard} from 'src/app/api/auth/auth.guard';

const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        data: {title: ''},
        component: MeasurementUnitsListComponent
      },
      {
        path: ':id',
        component: MeasurementUnitsEditComponent,
        data: {breadcrumb: 'Редактировать еденицы измерения'}
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class MeasurementUnitsRoutingModule { }
