import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {TemperatureWeightAddFormComponent} from './temperature-weight-add-form.component';
import {SharedModule} from '../../shared.module';

@NgModule({
  declarations: [TemperatureWeightAddFormComponent],
  imports: [
    CommonModule,
    SharedModule,
  ],
  exports: [TemperatureWeightAddFormComponent]
})
export class TemperatureWeightAddFormModule {
}
