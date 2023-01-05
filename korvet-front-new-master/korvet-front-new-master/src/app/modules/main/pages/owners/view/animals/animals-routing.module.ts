import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {AnimalsComponent} from './animals.component';

const routes: Routes = [
  {
    path: '',
    component: AnimalsComponent,
  },
  {
    path: 'add',
    loadChildren: () => import('./add/add.module').then(m => m.AddModule),
    data: {breadcrumb: 'Добавить животное'}
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AnimalsRoutingModule {
}
