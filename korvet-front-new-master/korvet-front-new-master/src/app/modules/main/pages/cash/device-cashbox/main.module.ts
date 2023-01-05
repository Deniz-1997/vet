import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {ListComponent} from './list/list.component';
import {EditComponent} from './edit/edit.component';
import {SharedModule} from '../../../../shared/shared.module';

@NgModule({
  declarations: [ListComponent, EditComponent],
  imports: [
    CommonModule,
    RoutingModule,
    SharedModule,
  ]
})
export class MainModule {
}
