import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { PetReasonRetiringRoutingModule } from './pet-reason-retiring-routing.module';
import {SharedModule} from '../../../../../shared/shared.module';
import {PetReasonRetiringListComponent} from './pet-reason-retiring-list/pet-reason-retiring-list.component';
import {PetReasonRetiringEditComponent} from './pet-reason-retiring-edit/pet-reason-retiring-edit.component';
import {ReferenceModule} from '../../../../../shared/modules/reference-edit/reference.module';


@NgModule({
  declarations: [PetReasonRetiringListComponent, PetReasonRetiringEditComponent],
    imports: [
        CommonModule,
        PetReasonRetiringRoutingModule,
        SharedModule,
        ReferenceModule,
    ]
})
export class PetReasonRetiringModule { }
