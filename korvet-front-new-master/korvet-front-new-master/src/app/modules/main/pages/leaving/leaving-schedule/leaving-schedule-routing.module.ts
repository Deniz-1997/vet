import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import {LeavingScheduleComponent} from './leaving-schedule.component';

const routes: Routes = [{
  path: '',
  component: LeavingScheduleComponent
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class LeavingScheduleRoutingModule { }
