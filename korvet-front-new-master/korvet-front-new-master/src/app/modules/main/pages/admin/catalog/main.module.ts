import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {SharedModule} from '../../../../shared/shared.module';
import {HtmlComponent} from './html.component';

@NgModule({
  declarations: [HtmlComponent],
  imports: [
    CommonModule,
    SharedModule,
    RoutingModule,

  ]
})
export class MainModule {
}
