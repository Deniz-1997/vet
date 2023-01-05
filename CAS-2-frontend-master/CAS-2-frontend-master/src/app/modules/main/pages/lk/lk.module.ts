import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {LkRoutingModule} from './lk-routing.module';
import {LkComponent} from './lk.component';

@NgModule({
  declarations: [LkComponent],
  imports: [
    CommonModule,
    LkRoutingModule
  ]
})
export class LkModule {
}
