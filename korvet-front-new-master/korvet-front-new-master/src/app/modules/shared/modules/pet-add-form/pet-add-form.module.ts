import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {PetAddFormComponent} from './pet-add-form.component';
import {SharedModule} from '../../shared.module';

@NgModule({
  declarations: [PetAddFormComponent],
  imports: [
    CommonModule,
    SharedModule,
  ],
  exports: [PetAddFormComponent],
})
export class PetAddFormModule {
}
