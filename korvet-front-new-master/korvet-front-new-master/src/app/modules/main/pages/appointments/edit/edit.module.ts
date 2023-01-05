import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ReactiveFormsModule} from '@angular/forms';

import {RoutingModule} from './routing.module';
import {EditComponent} from './edit.component';
import {SharedModule} from '../../../../shared/shared.module';
import {MainComponent as AppointmentsAddTemplatesComponent} from './add-templates/main.component';
import {MainComponent as AppointmentsSaveAsTemplateComponent} from './save-as-template/main.component';
import {MainComponent as AppointmentsShowTemplateComponent} from './show-template/main.component';
import {ViewItemsModule as AppointmentsViewItemsModule} from '../view-items/view-items.module';
import {MainComponent as AppointmentsAddFormTemplateComponent} from './add-form-template/main.component';
import {MainModule} from '../../admin/references/form-template/main.module';
import {MainComponent as AppointmentsShowFormTemplateComponent} from './show-form-template/main.component';
import { AppointmentFilesModule } from '../files/files.module';
import {ViewModule} from '../../pets/view/view.module';

@NgModule({
  declarations: [
    EditComponent,
    AppointmentsAddTemplatesComponent,
    AppointmentsSaveAsTemplateComponent,
    AppointmentsShowTemplateComponent,
    AppointmentsAddFormTemplateComponent,
    AppointmentsShowFormTemplateComponent,
  ],
    imports: [
        CommonModule,
        RoutingModule,
        AppointmentsViewItemsModule,
        SharedModule,
        ReactiveFormsModule,
        MainModule,
        AppointmentFilesModule,
        ViewModule,
    ],
  entryComponents: [
    AppointmentsShowTemplateComponent,
    AppointmentsShowFormTemplateComponent
  ],
  exports: [
    AppointmentsAddTemplatesComponent,
    AppointmentsSaveAsTemplateComponent,
    AppointmentsShowTemplateComponent,
    AppointmentsAddFormTemplateComponent,
    AppointmentsShowFormTemplateComponent,
  ]
})
export class EditModule {
}
