import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {ReceiptRoutingModule} from './receipt-routing.module';
import {ListComponent as ProductReceiptListComponent} from './list/list.component';
import {EditComponent as ProductReceiptEditComponent} from './edit/edit.component';
import {SharedModule} from '../../../../../shared/shared.module';
import {ViewComponent as ProductReceiptViewComponent} from './view/view.component';
import {ProductListModule} from '../../product-list/product-list.module';

@NgModule({
  declarations: [
    ProductReceiptListComponent,
    ProductReceiptEditComponent,
    ProductReceiptViewComponent],
  imports: [
    CommonModule,
    SharedModule,
    ReceiptRoutingModule,
    ProductListModule
  ]
})
export class ReceiptModule {
}
