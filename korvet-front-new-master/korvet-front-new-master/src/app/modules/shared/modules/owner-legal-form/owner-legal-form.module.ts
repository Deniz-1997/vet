import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {OwnerLegalFormComponent} from './owner-legal-form.component';
import {SharedModule} from '../../shared.module';
import {OwnerFileViewModule} from '../owner-file-view/owner-file-view.module';
import { PersonalContactModule } from '../personal-contact/personal-contact.module';

@NgModule({
  declarations: [OwnerLegalFormComponent],
  imports: [
    CommonModule,
    SharedModule,
    OwnerFileViewModule,
    PersonalContactModule
  ],
  exports: [OwnerLegalFormComponent],
})
export class OwnerLegalFormModule {
}
