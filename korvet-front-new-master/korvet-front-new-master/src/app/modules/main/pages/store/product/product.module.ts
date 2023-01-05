import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {ProductRoutingModule} from './product-routing.module';
import {ListComponent, ListComponent as ProductListComponent} from './list/list.component';
import {EditComponent as ProductEditComponent} from './edit/edit.component';
import {SharedModule} from '../../../../shared/shared.module';
import {ProductToServicesFormModule} from '../../../../shared/modules/product-to-services-form/product-to-services-form.module';
import {DialogExportComponent} from './dialog-export/dialog-export.component';

@NgModule({
  declarations: [ProductListComponent, ProductEditComponent, DialogExportComponent],
  imports: [
    CommonModule,
    SharedModule,
    ProductRoutingModule,
    ProductToServicesFormModule,
  ],
  exports: [
    ListComponent
  ],
  entryComponents: [
    DialogExportComponent,
  ]
})
export class ProductModule {
}
