import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {ViewRoutingModule} from './view-routing.module';
import {ViewComponent} from './view.component';
import {SharedModule} from '../../../../shared/shared.module';
import {ViewService} from './view.service';
import {DetailComponent} from './history/detail/detail.component';
import {MainModule} from '../../admin/references/form-template/main.module';
import { AppointmentHistoryModule } from 'src/app/modules/shared/modules/appointment-history/appointment-history.module';

@NgModule({
  declarations: [
    ViewComponent,
    DetailComponent
  ],
  imports: [
    CommonModule,
    ViewRoutingModule,
    SharedModule,
    MainModule,
    AppointmentHistoryModule
  ],
  providers: [
    ViewService,
  ]
})
export class ViewModule {
}
