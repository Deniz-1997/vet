import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {AuthGuard} from 'src/app/api/auth/auth.guard';
import {ListComponent} from './list/list.component';

const routes: Routes = [{
  path: '',
  canActivate: [AuthGuard],
  children: [
    {
      path: '',
      component: ListComponent,
      data: {breadcrumb: ' Отлов животных'}
    },
    {
      path: 'create',
      loadChildren: () => import('./edit/edit.module').then(m => m.EditModule),
      data: {breadcrumb: 'Создание'},
    },
    {
      path: ':id',
      loadChildren: () => import('./view/view.module').then(m => m.ViewModule),
      data: {breadcrumb: 'Профиль животного'},
    },
  ]
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class WildAnimalRoutingModule {
}
