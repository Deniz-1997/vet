import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {OwnerIndividualFormComponent} from './owner-individual-form.component';
import {SharedModule} from '../../shared.module';
import { PersonalContactModule } from '../personal-contact/personal-contact.module';

@NgModule({
  declarations: [OwnerIndividualFormComponent],
  imports: [
    CommonModule,
    SharedModule,
    PersonalContactModule
  ],
  exports: [OwnerIndividualFormComponent]
})
export class OwnerIndividualFormModule {
}
