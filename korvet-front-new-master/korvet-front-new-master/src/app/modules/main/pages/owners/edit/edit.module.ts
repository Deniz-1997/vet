import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {EditComponent} from './edit.component';
import {SharedModule} from '../../../../shared/shared.module';
import {OwnerIndividualFormModule} from '../../../../shared/modules/owner-individual-form/owner-individual-form.module';
import {OwnerLegalFormModule} from '../../../../shared/modules/owner-legal-form/owner-legal-form.module';
import {OwnerEntrepreneurFormModule} from '../../../../shared/modules/owner-entrepreneur-form/owner-entrepreneur-form.module';
import {OwnerFarmFormModule} from '../../../../shared/modules/owner-farm-form/owner-farm-form.module';

@NgModule({
  declarations: [EditComponent],
  imports: [
    CommonModule,
    RoutingModule,
    SharedModule,
    OwnerIndividualFormModule,
    OwnerLegalFormModule,
    OwnerEntrepreneurFormModule,
    OwnerFarmFormModule,
  ],
})
export class EditModule {
}
