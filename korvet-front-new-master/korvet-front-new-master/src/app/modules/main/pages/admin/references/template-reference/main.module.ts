import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ListComponent} from './list/list.component';
import {EditComponent} from './edit/edit.component';
import {RoutingModule} from './routing.module';
import {SharedModule} from '../../../../../shared/shared.module';
import { ListComponent as ListReferenceValues} from '../template-reference-values/list/list.component';
import {ReferenceModule} from '../../../../../shared/modules/reference-edit/reference.module';

@NgModule({
    declarations: [ListComponent, EditComponent, ListReferenceValues],
    exports: [
        ListComponent
    ],
    imports: [
        CommonModule,
        SharedModule,
        RoutingModule,
        ReferenceModule
    ]
})
export class MainModule {
}
