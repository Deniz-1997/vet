import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {CreateComponent} from './create.component';
import {AppointmentAddFormModule} from 'src/app/modules/shared/modules/appointment-add-form/appointment-add-form.module';
import {RoutingModule} from './routing.module';


@NgModule({
  declarations: [CreateComponent],
  imports: [
    CommonModule,
    RoutingModule,
    AppointmentAddFormModule
  ]
})
export class CreateModule {
}
