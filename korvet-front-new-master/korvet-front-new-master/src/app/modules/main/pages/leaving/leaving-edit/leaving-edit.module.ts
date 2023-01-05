import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ReactiveFormsModule} from '@angular/forms';
import {LeavingRoutingModule} from './leaving-routing.module';
import {LeavingEditComponent} from './leaving-edit.component';
import {SharedModule} from '../../../../shared/shared.module';
import {MainModule} from '../../admin/references/form-template/main.module';
import {ViewModule} from '../../pets/view/view.module';
import {LeavingViewItemsModule} from '../leaving-view-items/leaving-view-items.module';
import {MainComponent as AppointmentsShowTemplateComponent} from '../../appointments/edit/show-template/main.component';
import {MainComponent as AppointmentsShowFormTemplateComponent} from '../../appointments/edit/show-form-template/main.component';
import {EditModule} from '../../appointments/edit/edit.module';
import {AppointmentFilesModule} from '../../appointments/files/files.module';
import {OwnerAndPetCreatedModule} from '../../../../shared/modules/owner-and-pet-created/owner-and-pet-created.module';

@NgModule({
  declarations: [
    LeavingEditComponent,
  ],
  imports: [
    CommonModule,
    LeavingRoutingModule,
    SharedModule,
    ReactiveFormsModule,
    MainModule,
    ViewModule,
    LeavingViewItemsModule,
    EditModule,
    AppointmentFilesModule,
    OwnerAndPetCreatedModule,
  ],
  entryComponents: [
    AppointmentsShowTemplateComponent,
    AppointmentsShowFormTemplateComponent
  ]
})
export class LeavingEditModule {
}
