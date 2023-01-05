import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {AnimalsRoutingModule} from './animals-routing.module';
import {AnimalsComponent} from './animals.component';
import {SharedModule} from '../../../../../shared/shared.module';

@NgModule({
  declarations: [AnimalsComponent],
  imports: [
    CommonModule,
    AnimalsRoutingModule,
    SharedModule,
  ]
})
export class AnimalsModule {
}
