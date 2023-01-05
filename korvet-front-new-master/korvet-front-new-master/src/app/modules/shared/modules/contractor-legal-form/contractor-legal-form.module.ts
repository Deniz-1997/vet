import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ContractorLegalFormComponent} from './contractor-legal-form.component';
import {SharedModule} from '../../shared.module';
import {OwnerFileViewModule} from '../owner-file-view/owner-file-view.module';

@NgModule({
  declarations: [ContractorLegalFormComponent],
  imports: [
    CommonModule,
    SharedModule,
    OwnerFileViewModule,
  ],
  exports: [ContractorLegalFormComponent],
})
export class ContractorLegalFormModule {
}
