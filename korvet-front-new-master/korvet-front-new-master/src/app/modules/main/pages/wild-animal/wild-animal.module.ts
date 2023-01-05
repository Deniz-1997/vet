import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {WildAnimalRoutingModule} from './wild-animal-routing.module';
import {ListComponent} from './list/list.component';
import {SharedModule} from '../../../shared/shared.module';

@NgModule({
  declarations: [ListComponent],
  imports: [
    CommonModule,
    SharedModule,
    WildAnimalRoutingModule
  ]
})
export class WildAnimalModule {
}
