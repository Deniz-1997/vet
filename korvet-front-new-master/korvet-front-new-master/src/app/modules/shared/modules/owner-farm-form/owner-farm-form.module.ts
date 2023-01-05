import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {OwnerFarmFormComponent} from './owner-farm-form.component';
import {SharedModule} from '../../shared.module';
import {OwnerFileViewModule} from '../owner-file-view/owner-file-view.module';
import { PersonalContactModule } from '../personal-contact/personal-contact.module';

@NgModule({
  declarations: [OwnerFarmFormComponent],
  imports: [
    CommonModule,
    SharedModule,
    OwnerFileViewModule
  ],
  exports: [OwnerFarmFormComponent],
})
export class OwnerFarmFormModule {
}
