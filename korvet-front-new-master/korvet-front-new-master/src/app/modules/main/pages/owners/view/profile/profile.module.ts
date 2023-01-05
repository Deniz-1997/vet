import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {ProfileRoutingModule} from './profile-routing.module';
import {ProfileComponent} from './profile.component';
import {EntrepreneurComponent} from './entrepreneur/entrepreneur.component';
import {SharedModule} from '../../../../../shared/shared.module';
import {IndividualComponent} from './individual/individual.component';
import {FarmComponent} from './farm/farm.component';
import {LegalComponent} from './legal/legal.component';
import {ModalEventActionsViewModule} from '../../../../../shared/modules/modal-event-actions-view/modal-event-actions-view.module';
import {ViewModule} from '../../../pets/view/view.module';

@NgModule({
  declarations: [
    ProfileComponent,
    EntrepreneurComponent,
    IndividualComponent,
    FarmComponent,
    LegalComponent,
  ],
  imports: [
    CommonModule,
    ProfileRoutingModule,
    SharedModule,
    ModalEventActionsViewModule,
    ViewModule
  ],
  entryComponents: [
    EntrepreneurComponent,
    IndividualComponent,
    FarmComponent,
    LegalComponent,
  ]
})
export class ProfileModule {
}
