import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {RolesGuard} from 'src/app/api/api-menu/roles.guard';
import {AuthGuard} from 'src/app/api/auth/auth.guard';

const routes: Routes = [
  {
    path: '',
    canActivate: [AuthGuard, RolesGuard],
    children: [

      {
        path: '',
        data: {title: 'Склад', 'breadcrumb': 'Склад'},
        loadChildren: () => import('./list/list.module').then(m => m.ListModule)
      },
      {
        path: 'product',
        canActivate: [RolesGuard],
        data: {title: 'Номенклатура', 'breadcrumb': 'Номенклатура'},
        loadChildren: () => import('./product/product.module').then(m => m.ProductModule)
      },
      {
        path: 'ftp-history',
        canActivate: [RolesGuard],
        data: {title: 'История импорта/экспорта отчетов 1С', 'breadcrumb': 'История импорта/экспорта отчетов 1С'},
        loadChildren: () => import('./ftp-history/ftp-history.module').then(m => m.FtpHistoryModule)
      },
      {
        path: 'balance',
        canActivate: [RolesGuard],
        data: {title: 'Остатки', 'breadcrumb': 'Остатки'},
        loadChildren: () => import('./balance/balance.module').then(m => m.BalanceModule)
      },
      {
        path: 'product-receipt',
        canActivate: [RolesGuard],
        data: {title: 'Поступление товаров', 'breadcrumb': 'Поступление товаров'},
        loadChildren: () => import('./product/receipt/receipt.module').then(m => m.ReceiptModule)
      },
      {
        path: 'product-expense',
        canActivate: [RolesGuard],
        data: {title: 'Расход товаров', 'breadcrumb': 'Расход товаров'},
        loadChildren: () => import('./product/expense/expense.module').then(m => m.ExpenseModule)
      },
      {
        path: 'product-transfer',
        canActivate: [RolesGuard],
        data: {title: 'Перемещение товаров', 'breadcrumb': 'Перемещение товаров'},
        loadChildren: () => import('./product/transfer/transfer.module').then(m => m.TransferModule)
      },
      {
        path: 'product-inventory',
        canActivate: [RolesGuard],
        data: {title: 'Инвентаризация', 'breadcrumb': 'Инвентаризация'},
        loadChildren: () => import('./product/inventory/inventory.module').then(m => m.InventoryModule)
      },
      {
        path: 'product-history',
        canActivate: [RolesGuard],
        data: {title: 'Движения остатков', 'breadcrumb': 'Движения остатков'},
        loadChildren: () => import('./product/history/history.module').then(m => m.HistoryModule)
      },
      {
        path: 'reference-nomenclature',
        data: {title: 'Справочники номенклатуры', 'breadcrumb': 'Справочники номенклатуры'},
        loadChildren: () => import('../admin/references/nomenclature/nomenclature.module').then(m => m.NomenclatureModule)
      },
      {
        path: 'reference-measurement-units',
        data: {title: 'Единицы измерения', 'breadcrumb': 'Единицы измерения'},
        loadChildren: () => import('../admin/references/measurement-units/measurement-units.module').then(m => m.MeasurementUnitsModule)
      },
      {
        path: 'reference-countries',
        data: {title: 'Страны', 'breadcrumb': 'Страны'},
        loadChildren: () => import('../admin/references/countries/countries.module').then(m => m.CountriesModule)
      },
      {
        path: 'reference-category-nomenclature',
        data: {title: 'Категории номенклатуры', 'breadcrumb': 'Категории номенклатуры'},
        loadChildren: () => import('../admin/references/category-nomenclature/main.module').then(m => m.MainModule)
      },
      {
        path: 'reference-release-form',
        data: {title: 'Форма выпуска', 'breadcrumb': 'Форма выпуска'},
        loadChildren: () => import('../admin/references/release-form/main.module').then(m => m.MainModule)
      },
      {
        path: 'reference-manufacturer',
        data: {title: 'Производитель', 'breadcrumb': 'Производитель'},
        loadChildren: () => import('../admin/references/manufacturer/main.module').then(m => m.MainModule)
      },
      {
        path: 'reference-disease',
        data: {title: 'Заболевания', 'breadcrumb': 'Заболевания'},
        loadChildren: () => import('../admin/references/disease/disease.module').then(m => m.DiseaseModule)
      },
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class StoreRoutingModule {
}
