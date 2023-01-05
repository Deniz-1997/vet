import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {AddComponent} from './add.component';
import {SharedModule} from '../../../../../../shared/shared.module';
import {PetAddFormModule} from '../../../../../../shared/modules/pet-add-form/pet-add-form.module';

@NgModule({
  declarations: [AddComponent],
  imports: [
    CommonModule,
    RoutingModule,
    SharedModule,
    PetAddFormModule,
  ]
})
export class AddModule {
}
