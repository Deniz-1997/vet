import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {LeavingRoutingModule} from './leaving-routing.module';
import {SharedModule} from '../../../../shared/shared.module';
import {MainModule} from '../../admin/references/form-template/main.module';
import {ViewModule as PetsViewModule} from '../../pets/view/view.module';
import {AppointmentHistoryModule} from '../../../../shared/modules/appointment-history/appointment-history.module';
import {LeavingViewItemsModule} from '../leaving-view-items/leaving-view-items.module';
import {LeavingViewComponent} from './leaving-view.component';
import {AppointmentFilesModule} from '../../appointments/files/files.module';


@NgModule({
  declarations: [LeavingViewComponent,
  ],
    imports: [
        CommonModule,
        LeavingRoutingModule,
        LeavingViewItemsModule,
        SharedModule,
        MainModule,
        PetsViewModule,
        AppointmentHistoryModule,
        AppointmentFilesModule,
    ]
})
export class LeavingViewModule {
}
