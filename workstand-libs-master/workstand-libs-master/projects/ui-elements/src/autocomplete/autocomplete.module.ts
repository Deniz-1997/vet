import { NgModule } from '@angular/core';
import {MatInputModule} from "@angular/material/input";
import {MatIconModule} from "@angular/material/icon";
import {MatButtonModule} from "@angular/material/button";
import {CommonModule} from "@angular/common";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {AutocompleteComponent} from "./autocomplete.component";
import {IconModule} from "../icon/icon.module";
import { MatAutocompleteModule} from "@angular/material/autocomplete";
import { MatOptionModule} from "@angular/material/core";



@NgModule({
  declarations: [
    AutocompleteComponent
  ],
  imports: [
    IconModule,
    MatInputModule,
    MatIconModule,
    MatButtonModule,
    CommonModule,
    ReactiveFormsModule,
    FormsModule,
    MatOptionModule,
    MatAutocompleteModule,
  ],
  exports: [
    AutocompleteComponent
  ]
})
export class AutocompleteModule { }
