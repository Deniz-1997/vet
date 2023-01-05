import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {ListComponent} from './list/list.component';
import {EditComponent} from './edit/edit.component';
import {ViewComponent} from './view/view.component';
import {SharedModule} from '../../../../shared/shared.module';
import {ItemsViewModule} from './items-view/items-view.module';

@NgModule({
  declarations: [ListComponent, EditComponent, ViewComponent],
  imports: [
    CommonModule,
    SharedModule,
    RoutingModule,
    ItemsViewModule
  ]
})
export class ReceiptModule {
}
