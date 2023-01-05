import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ReferenceListComponent} from './reference-list.component';
import {SharedModule} from '../../shared.module';
import {RouterModule} from '@angular/router';
import {ViewModule} from './view/view.module';
import {ButtonModule, ColModule, ContainerModule, HeaderModule, IconModule, RowModule, SubheaderModule, TextFieldModule} from '@korvet/ui-elements';
import {FlexLayoutModule} from '@angular/flex-layout';



@NgModule({
  declarations: [ReferenceListComponent],
  imports: [
    CommonModule,
    SharedModule,
    RouterModule,
    ViewModule,
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
  exports: [ReferenceListComponent]
})
export class ReferenceModule { }
