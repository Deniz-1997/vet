import { NgModule } from '@angular/core';
import { MaterialTableComponent } from './material-table.component';
import {CommonModule} from "@angular/common";
import {MatGridListModule} from "@angular/material/grid-list";
import {MatTableModule} from "@angular/material/table";
import {FlexModule} from "@angular/flex-layout";



@NgModule({
  declarations: [
    MaterialTableComponent
  ],
  imports: [CommonModule, MatGridListModule, MatTableModule, FlexModule
  ],
  exports: [
    MaterialTableComponent
  ]
})
export class MaterialTableModule { }
