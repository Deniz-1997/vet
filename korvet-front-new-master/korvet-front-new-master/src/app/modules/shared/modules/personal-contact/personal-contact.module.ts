import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PersonalContactComponent } from './personal-contact.component';
import { SharedModule } from '../../shared.module';



@NgModule({
  declarations: [PersonalContactComponent],
  imports: [
    CommonModule,
    SharedModule
  ],
  exports: [PersonalContactComponent]
})
export class PersonalContactModule { }
