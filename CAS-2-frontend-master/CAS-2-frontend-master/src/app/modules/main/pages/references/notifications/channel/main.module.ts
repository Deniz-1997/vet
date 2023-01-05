import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ListComponent} from './list/list.component';
import {EditComponent} from './edit/edit.component';

import {RoutingModule} from './routing.module';
import {SharedModule} from '../../../../../shared/shared.module';
import {ReferenceEditModule} from '../../../../../shared/modules/reference-edit/edit/reference-edit.module';
import {ReferenceModule} from 'src/app/modules/shared/modules/reference-edit/reference.module';

@NgModule({
  declarations: [ListComponent, EditComponent],
    imports: [
        CommonModule,
        RoutingModule,
        SharedModule,
        ReferenceEditModule,
        ReferenceModule
    ]
})
export class MainModule {
}
