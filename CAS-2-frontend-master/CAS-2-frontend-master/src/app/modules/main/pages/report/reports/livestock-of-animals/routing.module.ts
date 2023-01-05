import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ListComponent} from './list/list.component';
import {AuthGuard} from '../../../../../../api/auth/auth.guard';
import {ReportFormComponent} from '../../report-form/report-form.component';
import {routerList} from '../../../../../../utils/methodRoterList.';

const routes: Routes = routerList(ListComponent, ReportFormComponent);

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class RoutingModule {
}
