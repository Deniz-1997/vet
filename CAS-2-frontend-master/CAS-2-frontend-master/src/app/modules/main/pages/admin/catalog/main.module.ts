import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {SharedModule} from '../../../../shared/shared.module';
import {HtmlComponent} from './html.component';
import {ColModule, RowModule} from '@korvet/ui-elements';

@NgModule({
  declarations: [HtmlComponent],
  imports: [
    CommonModule,
    SharedModule,
    RoutingModule,
    RowModule,
    ColModule,

  ]
})
export class MainModule {
}
