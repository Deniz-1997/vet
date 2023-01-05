import {NgModule} from '@angular/core';
import {TableComponent} from './table.component';
import {CommonModule} from "@angular/common";
import {MatGridListModule} from "@angular/material/grid-list";
import {FlexModule} from '@angular/flex-layout';


@NgModule({
  declarations: [
    TableComponent
  ],
  imports: [CommonModule, MatGridListModule,
    FlexModule,
  ],
  exports: [
    TableComponent
  ]
})
export class TableModule {
}
