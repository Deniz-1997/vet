import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {ListComponent} from './list/list.component';
import {EditComponent} from './edit/edit.component';
import {SharedModule} from '../../../../../shared/shared.module';
import {ReferenceModule} from '../../../../../shared/modules/reference-edit/reference.module';

@NgModule({
  declarations: [EditComponent, ListComponent],
    imports: [
        CommonModule,
        RoutingModule,
        SharedModule,
        ReferenceModule
    ]
})
export class MainModule {
}
