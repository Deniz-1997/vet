import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {AddFormRoutingModule} from './add-form-routing.module';
import {AddFormComponent} from './add-form.component';
import {LeavingAddFormModule} from '../../../../../../shared/modules/leaving-add-form/leaving-add-form.module';

@NgModule({
  declarations: [AddFormComponent],
  imports: [
    CommonModule,
    AddFormRoutingModule,
    LeavingAddFormModule,
  ]
})
export class AddFormModule {
}
