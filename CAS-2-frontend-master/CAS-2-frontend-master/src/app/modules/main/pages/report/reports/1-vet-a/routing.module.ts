import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ListComponent} from './list/list.component';
import {routerList} from '../../../../../../utils/methodRoterList.';
import {ReportFormComponent} from '../../report-form/report-form.component';

const routes: Routes = routerList(ListComponent, ReportFormComponent);

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class RoutingModule {
}
