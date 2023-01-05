import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import { CullingComponent } from './culling.component';

const routes: Routes = [
  {
    path: '',
    data: {'breadcrumb': 'Отчет по отлову животных'},
    component: CullingComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class CullingRoutingModule {
}
