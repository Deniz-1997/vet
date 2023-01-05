import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {EventsRoutingModule} from './events-routing.module';
import {EventsComponent} from './events.component';
import {SharedModule} from '../../../../../shared/shared.module';
import {EventFormModule} from '../../../../../shared/modules/event-form/event-form.module';

@NgModule({
  declarations: [EventsComponent],
  imports: [
    CommonModule,
    EventsRoutingModule,
    SharedModule,
    EventFormModule,
  ]
})
export class EventsModule {
}
