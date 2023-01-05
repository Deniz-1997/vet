import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {AuthGuard} from 'src/app/api/auth/auth.guard';
import {MainComponent} from './components/main/main.component';

const routes: Routes = [{
  path: '',
  component: MainComponent,
  canActivate: [AuthGuard],
  data: {breadcrumb: 'Главная'},
  children: [
    {
      path: '',
      loadChildren: () => import('./pages/appointments/appointments.module').then(m => m.AppointmentsModule)
    },
    {
      path: 'appointments',
      loadChildren: () => import('./pages/appointments/appointments.module').then(m => m.AppointmentsModule)
    },
    {
      path: '',
      loadChildren: () => import('./pages/leaving/leaving.module').then(m => m.LeavingModule)
    },
    {
      path: 'leaving',
      loadChildren: () => import('./pages/leaving/leaving.module').then(m => m.LeavingModule)
    },
    {
      path: 'lk',
      data: {'breadcrumb': 'Профиль пользователя', 'title': 'Профиль пользователя', pageTitle: 'Профиль пользователя'},
      loadChildren: () => import('./pages/lk/lk.module').then(m => m.LkModule),
    },
    {
      path: 'pets',
      loadChildren: () => import('./pages/pets/pets.module').then(m => m.PetsModule)
    },
    {
      path: 'owners',
      data: {breadcrumb: 'Владельцы', 'title': 'Владельцы', pageTitle: 'Владельцы'},
      loadChildren: () => import('./pages/owners/owners.module').then(m => m.OwnersModule),
    },
    {
      path: 'admin',
      loadChildren: () => import('./pages/admin/admin.module').then(m => m.AdminModule),
      data: {breadcrumb: 'Администрирование', pageTitle: 'Администрирование', title: 'Администрирование'}
    },
    {
      path: 'cash',
      loadChildren: () => import('./pages/cash/cash.module').then(m => m.CashModule),
      data: {breadcrumb: 'Касса', pageTitle: 'Касса', title: 'Касса'}
    },
    {
      path: 'store',
      loadChildren: () => import('./pages/store/store.module').then(m => m.StoreModule),
      data: {breadcrumb: 'Склад', pageTitle: 'Склад', title: 'Склад'}
    },
    {
      path: 'culling',
      loadChildren: () => import('./pages/wild-animal/wild-animal.module').then(m => m.WildAnimalModule),
      data: {breadcrumb: 'Отлов животных', pageTitle: 'Отлов животных', title: 'Отлов животных'}
    },
    {
      path: 'reports',
      loadChildren: () => import('./pages/reports/reports.module').then(m => m.ReportsModule),
      data: {breadcrumb: 'Отчеты', pageTitle: 'Отчеты', title: 'Отчеты'}
    },
    {
      path: 'shop',
      loadChildren: () => import('./pages/shop/shop.module').then(m => m.ShopModule),
      data: {breadcrumb: 'Магазин', pageTitle: 'Магазин', title: 'Магазин'}
    },
    {
      path: 'laboratory',
      loadChildren: () => import('./pages/laboratory/laboratory.module').then(m => m.LaboratoryModule),
      data: {breadcrumb: 'Лаборатория', pageTitle: 'Лаборатория', title: 'Лаборатория'}
    },
  ],
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class MainRoutingModule {
}
