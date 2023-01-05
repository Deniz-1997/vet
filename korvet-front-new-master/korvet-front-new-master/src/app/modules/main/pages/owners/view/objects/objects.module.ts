import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {ObjectsRoutingModule} from './objects-routing.module';
import {ObjectsComponent} from './objects.component';
import {SharedModule} from '../../../../../shared/shared.module';

@NgModule({
  declarations: [
    ObjectsComponent,
  ],
  imports: [
    CommonModule,
    ObjectsRoutingModule,
    SharedModule,
  ],
})
export class ObjectsModule {
}
