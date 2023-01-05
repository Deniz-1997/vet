import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {PasswordComponent} from './password.component';

const routes: Routes = [{
  path: '',
  component: PasswordComponent,
  data: {title: 'Изменить пароль'}
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class RoutingModule {
}
