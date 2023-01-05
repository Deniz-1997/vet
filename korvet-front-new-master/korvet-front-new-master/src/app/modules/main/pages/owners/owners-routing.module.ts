import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {OwnersComponent} from './owners.component';

const routes: Routes = [{
  path: '',
  component: OwnersComponent,
  children: [
    {
      path: '',
      loadChildren: () => import('./list/list.module').then(m => m.ListModule)
    },
    {
      path: 'create',
      loadChildren: () => import('./edit/edit.module').then(m => m.EditModule),
      data: {breadcrumb: 'Создание'},
    },
    {
      path: ':id',
      loadChildren: () => import('./view/view.module').then(m => m.ViewModule),
      data: {breadcrumb: ''}
    }
  ],
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class OwnersRoutingModule {
}
