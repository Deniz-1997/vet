import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {EventFormComponent} from './event-form.component';
import {SharedModule} from '../../shared.module';

@NgModule({
  declarations: [EventFormComponent],
  imports: [
    CommonModule,
    SharedModule,
  ],
  exports: [EventFormComponent],
})
export class EventFormModule {
}
