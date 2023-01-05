import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {OwnersRoutingModule} from './owners-routing.module';
import {OwnersComponent} from './owners.component';
import {SharedModule} from '../../../shared/shared.module';

@NgModule({
  declarations: [OwnersComponent],
  imports: [
    CommonModule,
    OwnersRoutingModule,
    SharedModule,
  ],
})
export class OwnersModule {
}
