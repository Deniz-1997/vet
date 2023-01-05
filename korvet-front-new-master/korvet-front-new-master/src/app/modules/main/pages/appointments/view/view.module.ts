import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {RoutingModule} from './routing.module';
import {ViewComponent} from './view.component';
import {SharedModule} from '../../../../shared/shared.module';
import {ViewItemsModule} from '../view-items/view-items.module';
import {MainModule} from '../../admin/references/form-template/main.module';
import {ViewModule as PetsViewModule} from '../../pets/view/view.module';
import { AppointmentFilesModule } from '../files/files.module';
import {AppointmentHistoryModule} from '../../../../shared/modules/appointment-history/appointment-history.module';
import { LaboratoryModule } from '../../laboratory/laboratory.module';

@NgModule({
  declarations: [
    ViewComponent
  ],
  imports: [
    CommonModule,
    RoutingModule,
    ViewItemsModule,
    SharedModule,
    MainModule,
    PetsViewModule,
    AppointmentFilesModule,
    AppointmentHistoryModule,
    LaboratoryModule
  ]
})
export class ViewModule {
}
