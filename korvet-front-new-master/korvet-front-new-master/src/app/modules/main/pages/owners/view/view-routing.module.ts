import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ViewComponent} from './view.component';
import {DetailComponent} from './history/detail/detail.component';

const routes: Routes = [
  {
    path: '',
    component: ViewComponent,
    children: [
      {
        path: '',
        redirectTo: 'profile',
        pathMatch: 'full'
      },
      {
        path: 'profile',
        loadChildren: () => import('./profile/profile.module').then(m => m.ProfileModule),
      },
      {
        path: 'objects',
        loadChildren: () => import('./objects/objects.module').then(m => m.ObjectsModule)
      },
      {
        path: 'documents',
        loadChildren: () => import('./documents/documents.module').then(m => m.DocumentsModule),
      },
      {
        path: 'accounts',
        loadChildren: () => import('./accounts/accounts.module').then(m => m.AccountsModule)
      },
      {
        path: 'history',
        loadChildren: () => import('./history/history.module').then(m => m.HistoryModule)
      },
      {
        path: 'payment-history',
        loadChildren: () => import('./payment-history/payment-history-owner.module').then(m => m.PaymentHistoryOwnerModule)
      },
      {
        path: 'history-detail',
        component: DetailComponent
      },
      {
        path: 'pets',
        loadChildren: () => import('./animals/animals.module').then(m => m.AnimalsModule)
      },
      {
        path: 'events',
        loadChildren: () => import('./events/events.module').then(m => m.EventsModule),
      },
      {
        path: 'researchs',
        loadChildren: () => import('./researchs/researchs.module').then(m => m.ResearchsModule),
      },
    ]
  },
  {
    path: 'edit',
    loadChildren: () => import('../edit/edit.module').then(m => m.EditModule),
    data: {breadcrumb: 'Редактирование'}
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ViewRoutingModule {
}
