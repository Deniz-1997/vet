import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {VaccinationsComponent} from './vaccinations/vaccinations.component';

const routes: Routes = [{
  path: '',
  children: [
    {path: 'vaccinations', component: VaccinationsComponent},
  ]
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PublicRoutingModule {
}
