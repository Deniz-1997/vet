import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {BreedFormComponent} from './breed-form.component';
import {SharedModule} from '../../shared.module';

@NgModule({
  declarations: [BreedFormComponent],
  imports: [
    CommonModule,
    SharedModule,
  ],
  exports: [BreedFormComponent],
})
export class BreedFormModule {
}
