import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import {LeavingListComponent} from './leaving-list.component';

const routes: Routes = [{
  path: '',
  component: LeavingListComponent
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class LeavingListRoutingModule { }
