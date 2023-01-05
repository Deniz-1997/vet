import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {SharedModule} from '../../shared.module';
import {RouterModule} from '@angular/router';
import {ButtonModule, ColModule, HeaderModule, RowModule, TextFieldModule} from '@korvet/ui-elements';
import {FlexLayoutModule} from '@angular/flex-layout';
import {ButtonsComponentComponent} from './buttons-component.component';



@NgModule({
  declarations: [ButtonsComponentComponent],
  imports: [
    CommonModule,
    SharedModule,
    RouterModule,
    RowModule,
    ColModule,
    HeaderModule,
    FlexLayoutModule,
    TextFieldModule,
    ButtonModule,
  ],
  exports: [ButtonsComponentComponent]
})
export class ButtonsComponentModule { }
