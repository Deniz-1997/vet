import {NgModule} from '@angular/core';
import {ColComponent} from './col.component';
import {CommonModule} from "@angular/common";


@NgModule({
  declarations: [
    ColComponent
  ],
  imports: [
    CommonModule
  ],
  exports: [
    ColComponent
  ]
})
export class ColModule {
}
