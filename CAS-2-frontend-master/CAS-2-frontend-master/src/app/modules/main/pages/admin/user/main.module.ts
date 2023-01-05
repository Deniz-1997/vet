import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {ListComponent} from './list/list.component';
import {EditComponent} from './edit/edit.component';
import {SharedModule} from '../../../../shared/shared.module';
import {ReferenceModule} from '../../../../shared/modules/reference-edit/reference.module';
import {ColModule, ContainerModule, HeaderModule, RowModule, SubheaderModule, TextFieldModule} from '@korvet/ui-elements';


@NgModule({
  declarations: [ListComponent, EditComponent],
  exports: [
  ],
  imports: [
    CommonModule,
    RoutingModule,
    SharedModule,
    ReferenceModule,
    TextFieldModule,
    ColModule,
    SubheaderModule,
    RowModule,
    ContainerModule,
    HeaderModule
  ]
})
export class MainModule {
}
