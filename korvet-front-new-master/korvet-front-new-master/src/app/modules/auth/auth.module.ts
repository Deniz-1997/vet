import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {AuthRoutingModule} from './auth-routing.module';
import {AuthComponent} from './components/auth/auth.component';
import {SharedModule} from '../shared/shared.module';
import {LoginComponent} from './components/login/login.component';
import {RestoreComponent} from './components/restore/restore.component';
import {AuthModule as Auth} from 'src/app/api/auth/auth.module';

@NgModule({
  declarations: [AuthComponent, LoginComponent, RestoreComponent],
  imports: [
    CommonModule,
    SharedModule,
    AuthRoutingModule,
    Auth,
  ]
})
export class AuthModule {
}
