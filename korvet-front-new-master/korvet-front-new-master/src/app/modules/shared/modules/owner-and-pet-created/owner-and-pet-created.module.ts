import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SharedModule } from '../../shared.module';
import {OwnerAndPetCreatedComponent} from './owner-and-pet-created.component';



@NgModule({
  declarations: [OwnerAndPetCreatedComponent],
  imports: [
    CommonModule,
    SharedModule
  ],
  exports: [OwnerAndPetCreatedComponent]
})
export class OwnerAndPetCreatedModule {}
