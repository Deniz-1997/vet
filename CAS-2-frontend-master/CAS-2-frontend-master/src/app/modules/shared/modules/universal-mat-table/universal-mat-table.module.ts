import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {SharedModule} from '../../shared.module';
import {RouterModule} from '@angular/router';
import {ButtonModule, ColModule, ContainerModule, HeaderModule, IconModule, RowModule, SubheaderModule, TextFieldModule} from '@korvet/ui-elements';
import {FlexLayoutModule} from '@angular/flex-layout';
import {UniversalMatTableComponent} from './universal-mat-table.component';



@NgModule({
  declarations: [UniversalMatTableComponent],
  imports: [
    CommonModule,
    SharedModule,
    RouterModule,
    HeaderModule,
    ContainerModule,
    ColModule,
    RowModule,
    TextFieldModule,
    ButtonModule,
    IconModule,
    FlexLayoutModule,
    SubheaderModule,
  ],
  exports: [UniversalMatTableComponent]
})
export class UniversalMatTableModule { }
