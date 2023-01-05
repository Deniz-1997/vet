import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {LeavingEditComponent} from './leaving-edit.component';

const routes: Routes = [{
  path: '',
  component: LeavingEditComponent,
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class LeavingRoutingModule {
}
