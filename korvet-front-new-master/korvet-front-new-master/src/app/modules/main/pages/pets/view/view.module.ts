import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ViewRoutingModule} from './view-routing.module';
import {ViewComponent} from './view.component';
import {SharedModule} from '../../../../shared/shared.module';
import {ProfileComponent} from './profile/profile.component';
import {DetailComponent as PetDetail} from './detail/detail.component';
import {DetailComponent as ProfileDetail} from './profile/detail/detail.component';
import {EventsComponent} from './events/events.component';
import {CardComponent} from './card/card.component';
import {TemperatureComponent} from './card/temperature/temperature.component';
import {WeightComponent} from './card/weight/weight.component';
import {AddComponent as WeightAdd} from './card/weight/add/add.component';
import {AddComponent as TemperatureAdd} from './card/temperature/add/add.component';
import {ChangeComponent as TemperatureChange} from './card/temperature/change/change.component';
import {ChangeComponent as WeightChange} from './card/weight/change/change.component';
import {AppointmentsComponent} from './appointments/appointments.component';
import {HistoryComponent} from './history/history.component';
import {DocumentComponent} from './document/document.component';
import {DocumentsComponent} from './document/documents/documents.component';
import {DetailComponent as DetailHistory} from './history/detail/detail.component';
import {ModalEventActionsViewModule} from '../../../../shared/modules/modal-event-actions-view/modal-event-actions-view.module';
import {TemperatureWeightAddFormModule} from 'src/app/modules/shared/modules/temperature-weight-add-form/temperature-weight-add-form.module';
import { AppointmentHistoryModule } from 'src/app/modules/shared/modules/appointment-history/appointment-history.module';
import {LeavingHistoryModule} from '../../owners/view/leaving/leaving-history.module';

@NgModule({
  declarations: [ViewComponent, ProfileComponent, PetDetail,
    ProfileDetail, EventsComponent, CardComponent,
    TemperatureComponent, WeightComponent,
    WeightAdd, TemperatureAdd,
    AppointmentsComponent, HistoryComponent,
    DocumentComponent, DocumentsComponent,
    TemperatureChange,
    WeightChange,
    DetailHistory],
    imports: [
        CommonModule,
        ViewRoutingModule,
        SharedModule,
        TemperatureWeightAddFormModule,
        ModalEventActionsViewModule,
        AppointmentHistoryModule,
        LeavingHistoryModule
    ],
  entryComponents: [
    TemperatureChange,
    TemperatureAdd,
    WeightChange,
    WeightAdd
  ],
    exports: [ViewComponent, PetDetail, AppointmentsComponent]
})
export class ViewModule {
}
