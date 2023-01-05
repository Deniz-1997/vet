import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {ListComponent} from './list/list.component';
import {EditComponent} from './edit/edit.component';
import {SharedModule} from 'src/app/modules/shared/shared.module';
import {ReferenceModule} from 'src/app/modules/shared/modules/reference-edit/reference.module';
import {ColModule, ContainerModule, RowModule, TextFieldModule} from '@korvet/ui-elements';

@NgModule({
  declarations: [ListComponent, EditComponent],
  imports: [
    CommonModule,
    SharedModule,
    RoutingModule,
    ReferenceModule,
    ContainerModule,
    RowModule,
    ColModule,
    TextFieldModule
  ]
})
export class MainModule {
}
