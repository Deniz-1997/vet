import { NgModule } from '@angular/core';
import { BlockyTableComponent } from './blocky-table.component';
import {CommonModule} from "@angular/common";
import {MatGridListModule} from "@angular/material/grid-list";



@NgModule({
  declarations: [
    BlockyTableComponent
  ],
  imports: [CommonModule, MatGridListModule
  ],
  exports: [
    BlockyTableComponent
  ]
})
export class BlockyTableModule { }
