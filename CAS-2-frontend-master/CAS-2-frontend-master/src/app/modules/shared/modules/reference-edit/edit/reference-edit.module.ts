import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {RouterModule} from '@angular/router';
import {SharedModule} from '../../../shared.module';
import {ReferenceEditComponent} from './reference-edit.component';
import {ColModule, ContainerModule, HeaderModule, RowModule, SubheaderModule, TextFieldModule} from '@korvet/ui-elements';



@NgModule({
  declarations: [ReferenceEditComponent],
  imports: [
    CommonModule,
    SharedModule,
    RouterModule,
    TextFieldModule,
    ColModule,
    HeaderModule,
    ContainerModule,
    RowModule,
    SubheaderModule,
  ],
  exports: [ReferenceEditComponent]
})
export class ReferenceEditModule { }
