import { NgModule } from '@angular/core';
import { DividerComponent } from './divider.component';
import {CommonModule} from "@angular/common";



@NgModule({
  declarations: [
    DividerComponent
  ],
  imports: [ CommonModule
  ],
  exports: [
    DividerComponent
  ]
})
export class DividerModule { }
