import { NgModule } from '@angular/core';
import {TextFieldComponent} from './textField.component';
import {MatInputModule} from "@angular/material/input";
import {MatIconModule} from "@angular/material/icon";
import {MatButtonModule} from "@angular/material/button";
import {CommonModule} from "@angular/common";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";



@NgModule({
  declarations: [
    TextFieldComponent
  ],
  imports: [
    MatInputModule,
    MatIconModule,
    MatButtonModule,
    CommonModule,
    ReactiveFormsModule,
    FormsModule,
  ],
  exports: [
    TextFieldComponent
  ]
})
export class TextFieldModule { }
