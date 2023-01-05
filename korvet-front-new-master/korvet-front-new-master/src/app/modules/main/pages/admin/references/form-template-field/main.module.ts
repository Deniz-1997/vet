import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {EditComponent} from './edit/edit.component';
import {ListComponent} from './list/list.component';
import {SharedModule} from '../../../../../shared/shared.module';

@NgModule({
  declarations: [EditComponent, ListComponent],
  imports: [
    CommonModule,
    SharedModule,
    RoutingModule
  ]
})
export class MainModule {
}
