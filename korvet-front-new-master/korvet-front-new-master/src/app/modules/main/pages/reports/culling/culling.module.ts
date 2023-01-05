import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {SharedModule} from '../../../../shared/shared.module';
import { CullingComponent } from './culling.component';
import { CullingRoutingModule } from './culling-routing.module';

@NgModule({
  declarations: [CullingComponent],
  imports: [
    CommonModule,
    SharedModule,
    CullingRoutingModule
  ]
})
export class CullingModule {
}
