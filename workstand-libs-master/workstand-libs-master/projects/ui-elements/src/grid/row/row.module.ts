import { NgModule } from '@angular/core';
import { RowComponent } from './row.component';
import {CommonModule} from "@angular/common";



@NgModule({
  declarations: [
    RowComponent,
  ],
  imports: [
    CommonModule,
  ],
  exports: [
    RowComponent,
  ]
})
export class RowModule { }
