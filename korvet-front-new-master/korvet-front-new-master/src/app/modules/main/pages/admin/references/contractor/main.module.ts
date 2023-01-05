import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {EditComponent} from './edit/edit.component';
import {ListComponent} from './list/list.component';
import {SharedModule} from '../../../../../shared/shared.module';
import {OwnerIndividualFormModule} from '../../../../../shared/modules/owner-individual-form/owner-individual-form.module';
import {OwnerLegalFormModule} from '../../../../../shared/modules/owner-legal-form/owner-legal-form.module';
import {OwnerEntrepreneurFormModule} from '../../../../../shared/modules/owner-entrepreneur-form/owner-entrepreneur-form.module';
import {OwnerFarmFormModule} from '../../../../../shared/modules/owner-farm-form/owner-farm-form.module';
import {ContractorLegalFormModule} from '../../../../../shared/modules/contractor-legal-form/contractor-legal-form.module';
import {ContractorEntrepreneurFormModule} from '../../../../../shared/modules/contractor-entrepreneur-form/contractor-entrepreneur-form.module';
import {ReferenceModule} from '../../../../../shared/modules/reference-edit/reference.module';

@NgModule({
  declarations: [EditComponent, ListComponent],
    imports: [
        CommonModule,
        RoutingModule,
        SharedModule,
        OwnerIndividualFormModule,
        OwnerLegalFormModule,
        OwnerEntrepreneurFormModule,
        OwnerFarmFormModule,
        ContractorLegalFormModule,
        ContractorEntrepreneurFormModule,
        ReferenceModule
    ]
})
export class MainModule {
}
