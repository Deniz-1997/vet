import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ExplanatoryNoteFormComponent} from './explanatory-note-form.component';
import {SharedModule} from '../../shared.module';
import {RouterModule} from '@angular/router';
import {ButtonModule, ColModule, HeaderModule, RowModule, TextFieldModule} from '@korvet/ui-elements';
import {FlexLayoutModule} from '@angular/flex-layout';
import {ButtonsComponentModule} from '../../components/buttons-component/buttons-component.module';



@NgModule({
  declarations: [ExplanatoryNoteFormComponent],
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
    ButtonsComponentModule,
  ],
  exports: [ExplanatoryNoteFormComponent]
})
export class ExplanatoryNoteFormModule { }
