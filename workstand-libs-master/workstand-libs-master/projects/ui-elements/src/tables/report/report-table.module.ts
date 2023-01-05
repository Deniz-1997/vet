import {NgModule} from '@angular/core';
import {ReportTableComponent} from './report-table.component';
import {CommonModule} from "@angular/common";
import {MatGridListModule} from "@angular/material/grid-list";
import {FlexModule} from '@angular/flex-layout';
import {MatFormFieldModule} from "@angular/material/form-field";
import {MatInputModule} from "@angular/material/input";
import {ReactiveFormsModule} from "@angular/forms";
import {NgArrayPipesModule} from "ngx-pipes";
import {MatTableModule} from "@angular/material/table";
import {MaterialTableModule} from "../material/material-table.module";
import {TableModule} from "../table/table.module";
import {BlockyTableModule} from "../blocky/blocky-table.module";
import {MatDatepickerModule} from "@angular/material/datepicker";
import {MatSelectModule} from "@angular/material/select";
import {ButtonModule} from "../../button/button.module";
import {AutocompleteModule} from "../../autocomplete/autocomplete.module";


@NgModule({
  declarations: [
    ReportTableComponent
  ],
    imports: [
        CommonModule,
        MatGridListModule, FlexModule,
        MatFormFieldModule, MatInputModule,
        ReactiveFormsModule, NgArrayPipesModule,
        MatTableModule, MaterialTableModule, TableModule, BlockyTableModule, MatDatepickerModule, MatSelectModule, ButtonModule, AutocompleteModule,
    ],
  exports: [
    ReportTableComponent
  ]
})
export class ReportTableModule {
}
