import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {AuthRoutingModule} from './auth-routing.module';
import {AuthComponent} from './components/auth/auth.component';
import {SharedModule} from '../shared/shared.module';
import {LoginComponent} from './components/login/login.component';
import {RestoreComponent} from './components/restore/restore.component';
import {AuthModule as Auth} from '../../api/auth/auth.module';
import {ColModule, RowModule, TextFieldModule} from '@korvet/ui-elements';

@NgModule({
  declarations: [AuthComponent, LoginComponent, RestoreComponent],
    imports: [
        CommonModule,
        SharedModule,
        AuthRoutingModule,
        Auth,
        ColModule,
        RowModule,
        TextFieldModule,
    ]
})
export class AuthModule {
}
