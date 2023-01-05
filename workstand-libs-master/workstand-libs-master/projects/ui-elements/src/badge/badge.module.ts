import { NgModule } from '@angular/core';
import { BadgeComponent } from './badge.component';
import {CommonModule} from "@angular/common";



@NgModule({
  declarations: [
    BadgeComponent
  ],
    imports: [
        CommonModule
    ],
  exports: [
    BadgeComponent
  ]
})
export class BadgeModule { }
