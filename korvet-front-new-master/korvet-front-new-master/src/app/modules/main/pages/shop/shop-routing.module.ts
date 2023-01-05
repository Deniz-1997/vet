import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ShopRealizationComponent } from './shop-realization/shop-realization.component';
import { ShopListComponent } from './shop-list/shop-list.component';
import {AuthGuard} from 'src/app/api/auth/auth.guard';
import {RolesGuard} from 'src/app/api/api-menu/roles.guard';

const routes: Routes = [{
  path: '',
  canActivate: [AuthGuard, RolesGuard],
  children: [
    {
      path: 'goods-issue',
      component: ShopRealizationComponent,
      data: {breadcrumb: 'Отпуск товаров'}
    },
    {
      path: 'goods-issue/:id',
      component: ShopRealizationComponent,
      data: {breadcrumb: 'Отпуск товаров'}
    },
    {
      path: 'sales-list',
      component: ShopListComponent,
      data: {title: 'Список продаж', 'breadcrumb': 'Список продаж'},
    },
    {
      path: 'shop-settings',
      data: {title: 'Настройки', 'breadcrumb': 'Настройки'},
      loadChildren: () => import('./settings/settings.module').then(m => m.SettingsModule)
    },
  ]
}];
@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ShopRoutingModule { }
