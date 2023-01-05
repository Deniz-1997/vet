import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {EditComponent} from './edit/edit.component';
import {SharedModule} from '../../../../shared/shared.module';
import {ListComponent} from './list/list.component';
import {ReferenceModule} from '../../../../shared/modules/reference-edit/reference.module';
import {ButtonModule, ColModule, ContainerModule, RowModule, SubheaderModule, TextFieldModule} from '@korvet/ui-elements';

@NgModule({
  declarations: [ListComponent, EditComponent],
  imports: [
    CommonModule,
    RoutingModule,
    SharedModule,
    ReferenceModule,
    ContainerModule,
    RowModule,
    ColModule,
    ButtonModule,
    SubheaderModule,
    TextFieldModule,
  ]
})
export class MainModule {
}
