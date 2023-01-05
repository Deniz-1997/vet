import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {EditComponent as ProductTransferEditComponent} from './edit/edit.component';
import {ListComponent as ProductTransferListComponent} from './list/list.component';
import {ViewComponent as ProductTransferViewComponent} from './view/view.component';
import {SharedModule} from '../../../../../shared/shared.module';
import {TransferRoutingModule} from './transfer-routing.module';
import {ProductListModule} from '../../product-list/product-list.module';

@NgModule({
  declarations: [ProductTransferEditComponent, ProductTransferListComponent, ProductTransferViewComponent],
  imports: [
    CommonModule,
    SharedModule,
    TransferRoutingModule,
    ProductListModule
  ]
})
export class TransferModule {
}
