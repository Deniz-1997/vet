import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {MainRoutingModule} from './main-routing.module';
import {MainComponent} from './main.component';
import {ColorpickerComponent} from './colorpicker/colorpicker.component';

@NgModule({
  declarations: [MainComponent, ColorpickerComponent],
  imports: [
    CommonModule,
    MainRoutingModule
  ]
})
export class MainModule {
}
