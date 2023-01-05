import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {SharedModule} from '../../shared.module';
import {LeavingAddFormComponent} from './leaving-add-form.component';
import {OwnerAndPetCreatedModule} from '../owner-and-pet-created/owner-and-pet-created.module';

@NgModule({
  declarations: [LeavingAddFormComponent],
    imports: [
        CommonModule,
        SharedModule,
        OwnerAndPetCreatedModule
    ],
  exports: [LeavingAddFormComponent],
})
export class LeavingAddFormModule {
}
