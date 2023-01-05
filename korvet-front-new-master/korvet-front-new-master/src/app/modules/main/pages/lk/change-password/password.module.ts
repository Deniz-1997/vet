import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {PasswordComponent} from './password.component';
import {SharedModule} from '../../../../shared/shared.module';

@NgModule({
  declarations: [PasswordComponent],
  imports: [
    CommonModule,
    RoutingModule,
    SharedModule,
  ]
})
export class PasswordModule {
}
