import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {OwnerEntrepreneurFormComponent} from './owner-entrepreneur-form.component';
import {SharedModule} from '../../shared.module';
import {OwnerFileViewModule} from '../owner-file-view/owner-file-view.module';
import { PersonalContactModule } from '../personal-contact/personal-contact.module';

@NgModule({
  declarations: [OwnerEntrepreneurFormComponent],
  imports: [
    CommonModule,
    SharedModule,
    OwnerFileViewModule,
    PersonalContactModule
  ],
  exports: [OwnerEntrepreneurFormComponent],
})
export class OwnerEntrepreneurFormModule {
}
