import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ProductToServicesFormComponent} from './product-to-services-form.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {SharedModule} from '../../shared.module';

@NgModule({
  declarations: [ProductToServicesFormComponent],
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    SharedModule,
  ],
  exports: [ProductToServicesFormComponent]
})
export class ProductToServicesFormModule {
}
