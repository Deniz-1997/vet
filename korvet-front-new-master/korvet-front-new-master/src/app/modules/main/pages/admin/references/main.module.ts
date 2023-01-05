import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {MainComponent} from './main.component';
import {SharedModule} from '../../../../shared/shared.module';

@NgModule({
  declarations: [MainComponent],
  imports: [
    CommonModule,
    SharedModule,
    RoutingModule
  ]
})
export class MainModule {
}
