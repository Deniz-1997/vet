import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {HtmlComponent} from './html.component';

const routes: Routes = [
  {
    path: '',
    data: {title: 'Справочники', breadcrumb: 'Справочники'},
    component: HtmlComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class RoutingModule {
}
