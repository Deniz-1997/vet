import { NgModule } from '@angular/core';
import {FormsComponent} from './forms.component';
import {ReactiveFormsModule} from "@angular/forms";



@NgModule({
  declarations: [
    FormsComponent
  ],
    imports: [
        ReactiveFormsModule
    ],
  exports: [
    FormsComponent
  ]
})
export class FormsModule { }
