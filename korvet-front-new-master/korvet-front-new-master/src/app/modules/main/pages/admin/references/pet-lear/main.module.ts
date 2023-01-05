import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {EditComponent} from './edit/edit.component';
import {ListComponent} from './list/list.component';
import {SharedModule} from '../../../../../shared/shared.module';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ReferenceModule} from '../../../../../shared/modules/reference-edit/reference.module';

@NgModule({
  declarations: [EditComponent, ListComponent],
    imports: [
        CommonModule,
        RoutingModule,
        SharedModule,
        FormsModule,
        ReactiveFormsModule,
        ReferenceModule,
    ]
})
export class MainModule {
}
