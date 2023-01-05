import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import { ResearchsComponent } from './researchs.component';

const routes: Routes = [{
  path: '',
  component: ResearchsComponent,
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ResearchsRoutingModule {
}
