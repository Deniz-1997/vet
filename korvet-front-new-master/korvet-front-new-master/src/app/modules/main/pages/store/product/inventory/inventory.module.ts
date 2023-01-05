import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {EditComponent as ProductInventoryEditComponent} from './edit/edit.component';
import {ListComponent as ProductInventoryListComponent} from './list/list.component';
import {SharedModule} from '../../../../../shared/shared.module';
import {InventoryRoutingModule} from './inventory-routing.module';
import {ItemsComponent as ProductInventoryItemsComponent} from './items/items.component';
import {ViewComponent as ProductInventoryViewComponent} from './view/view.component';

@NgModule({
  declarations: [
    ProductInventoryEditComponent,
    ProductInventoryListComponent,
    ProductInventoryItemsComponent,
    ProductInventoryViewComponent,
  ],
  imports: [
    CommonModule,
    SharedModule,
    InventoryRoutingModule
  ]
})
export class InventoryModule {
}
