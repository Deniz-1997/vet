import { NgModule } from '@angular/core';
import { ListItemIconComponent } from './listItemIcon.component';
import {CommonModule} from "@angular/common";



@NgModule({
  declarations: [
    ListItemIconComponent
  ],
  imports: [ CommonModule
  ],
  exports: [
    ListItemIconComponent
  ]
})
export class ListItemIconModule { }
