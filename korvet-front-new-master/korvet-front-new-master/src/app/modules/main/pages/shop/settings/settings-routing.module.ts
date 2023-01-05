import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {ShopSettingsListComponent} from './shop-settings-list/shop-settings-list.component';
import {ShopSettingsEditComponent} from './shop-settings-edit/shop-settings-edit.component';
import {AuthGuard} from 'src/app/api/auth/auth.guard';

const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        data: {title: ''},
        component: ShopSettingsListComponent
      },
      {
        path: ':id',
        component: ShopSettingsEditComponent,
        data: {breadcrumb: 'Редактировать настройки'}
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class SettingsRoutingModule { }
