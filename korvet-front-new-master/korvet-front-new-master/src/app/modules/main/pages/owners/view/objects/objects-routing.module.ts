import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ObjectsComponent} from './objects.component';

const routes: Routes = [
  {
    path: '',
    component: ObjectsComponent,
  },
  {
    path: 'add',
    loadChildren: () => import('./form/form.module').then(m => m.FormModule)
  },
  {
    path: ':objectId',
    loadChildren: () => import('./form/form.module').then(m => m.FormModule)
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ObjectsRoutingModule {
}
