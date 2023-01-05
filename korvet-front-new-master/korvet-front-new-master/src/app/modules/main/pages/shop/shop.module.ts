import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ShopRoutingModule } from './shop-routing.module';
import {SharedModule} from '../../../shared/shared.module';
import { ShopRealizationComponent } from './shop-realization/shop-realization.component';
import { ShopCatalogComponent } from './shop-catalog/shop-catalog.component';
import { ShopListComponent } from './shop-list/shop-list.component';



@NgModule({
  declarations: [ShopRealizationComponent, ShopCatalogComponent, ShopListComponent],
  imports: [
    CommonModule,
    SharedModule,
    ShopRoutingModule
  ]
})
export class ShopModule { }
