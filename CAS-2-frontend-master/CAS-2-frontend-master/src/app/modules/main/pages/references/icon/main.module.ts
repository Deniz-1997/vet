import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {ListComponent} from './list/list.component';
import {EditComponent} from './edit/edit.component';
import {ReferenceModule} from '../../../../shared/modules/reference-edit/reference.module';
import {SharedModule} from '../../../../shared/shared.module';
import {ReferenceEditModule} from '../../../../shared/modules/reference-edit/edit/reference-edit.module';

@NgModule({
  declarations: [ListComponent, EditComponent],
  exports: [
    ListComponent
  ],
    imports: [
        CommonModule,
        SharedModule,
        RoutingModule,
        ReferenceModule,
        ReferenceEditModule
    ]
})
export class MainModule {
}
