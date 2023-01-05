import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {PasswordComponent} from './password.component';
import {SharedModule} from '../../../../shared/shared.module';
import {ColModule, RowModule, TextFieldModule} from '@korvet/ui-elements';
import {FlexModule} from '@angular/flex-layout';

@NgModule({
  declarations: [PasswordComponent],
  imports: [
    CommonModule,
    RoutingModule,
    SharedModule,
    RowModule,
    ColModule,
    TextFieldModule,
    FlexModule,
  ]
})
export class PasswordModule {
}
