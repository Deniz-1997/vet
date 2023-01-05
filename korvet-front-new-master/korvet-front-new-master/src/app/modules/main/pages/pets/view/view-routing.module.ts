import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ProfileComponent} from './profile/profile.component';
import {ViewComponent} from './view.component';
import {CardComponent} from './card/card.component';
import {HistoryComponent} from './history/history.component';
import {DocumentComponent} from './document/document.component';
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
        component: ProfileComponent,
      },
      {
        path: 'card',
        component: CardComponent,
      },
      {
        path: 'history',
        component: HistoryComponent,
      },
      {
        path: 'history-detail',
        component: DetailComponent,
      },
      {
        path: 'payment-history',
        loadChildren: () => import('./payment-history/payment-history-pets.module').then(m => m.PaymentHistoryPetsModule)
      },
      {
        path: 'documents',
        component: DocumentComponent
      },
      {
        path: 'researchs',
        loadChildren: () => import('./researchs/researchs.module').then(m => m.ResearchsModule),
      },
    ]
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ViewRoutingModule {
}
