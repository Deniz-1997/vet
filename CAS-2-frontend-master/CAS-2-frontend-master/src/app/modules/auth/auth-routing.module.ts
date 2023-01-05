import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {AuthComponent} from './components/auth/auth.component';
import {LoginComponent} from './components/login/login.component';
import {RestoreComponent} from './components/restore/restore.component';

const routes: Routes = [{
  path: '', component: AuthComponent,
  children: [
    {path: '', component: LoginComponent},
    {path: 'restore', component: RestoreComponent}
  ]
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AuthRoutingModule {
}
