import { NgModule } from '@angular/core';
import { ListItemGroupComponent } from './listItemGroup.component';
import {CommonModule} from "@angular/common";


@NgModule({
  declarations: [
    ListItemGroupComponent
  ],
  imports: [ CommonModule
  ],
  exports: [
    ListItemGroupComponent
  ]
})
export class ListItemGroupModule { }
