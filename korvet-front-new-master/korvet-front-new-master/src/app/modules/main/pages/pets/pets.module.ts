import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {PetsRoutingModule} from './pets-routing.module';
import {SharedModule} from '../../../shared/shared.module';
import {ListComponent} from './list/list.component';
import {EditComponent} from './edit/edit.component';
import {ProfileComponent} from './profile/profile.component';
import {OwnerAddComponent} from './profile/owner-add/owner-add.component';
import {EventsComponent} from './profile/events/events.component';
import {AddComponent as EventAdd} from './profile/events/add/add.component';
import {AddComponent as TemperatureAdd} from './profile/temperature/add/add.component';
import {AddComponent as WeightAdd} from './profile/weight/add/add.component';
import {TemperatureComponent} from './profile/temperature/temperature.component';
import {WeightComponent} from './profile/weight/weight.component';
import {AppointmentsComponent} from './profile/appointments/appointments.component';
import {AddComponent as AppointmentAdd} from './profile/appointments/add/add.component';
import {DocumentComponent} from './profile/document/document.component';
import {AddComponent as DocumentAdd} from './profile/document/add/add.component';
import {TemperatureWeightAddFormModule} from 'src/app/modules/shared/modules/temperature-weight-add-form/temperature-weight-add-form.module';
import {AppointmentAddFormModule} from 'src/app/modules/shared/modules/appointment-add-form/appointment-add-form.module';
import {PetAddFormModule} from 'src/app/modules/shared/modules/pet-add-form/pet-add-form.module';
import {AddLeavingComponent} from './view/history/leaving-add/add-leaving.component';
import {LeavingAddFormModule} from '../../../shared/modules/leaving-add-form/leaving-add-form.module';

@NgModule({
  imports: [
    CommonModule,
    PetsRoutingModule,
    SharedModule,
    TemperatureWeightAddFormModule,
    AppointmentAddFormModule,
    LeavingAddFormModule,
    PetAddFormModule
  ],
  declarations: [ListComponent, EditComponent, ProfileComponent,
    OwnerAddComponent, EventsComponent, EventAdd,
    TemperatureAdd, TemperatureComponent, WeightComponent,
    WeightAdd, AppointmentsComponent, AppointmentAdd, AddLeavingComponent,
    DocumentComponent, DocumentAdd],
  entryComponents: [
    ListComponent
  ],
  exports: [EventsComponent]
})
export class PetsModule {
}
