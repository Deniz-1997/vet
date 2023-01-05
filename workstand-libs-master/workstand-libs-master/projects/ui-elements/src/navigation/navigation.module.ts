import { NgModule } from '@angular/core';
import { NavigationComponent } from './navigation.component';
import {CommonModule} from "@angular/common";



@NgModule({
  declarations: [
    NavigationComponent
  ],
  imports: [ CommonModule
  ],
  exports: [
    NavigationComponent
  ]
})
export class NavigationModule { }
