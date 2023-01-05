import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ModalEventActionsViewComponent} from './modal-event-actions-view.component';
import {SharedModule} from '../../shared.module';

@NgModule({
  declarations: [ModalEventActionsViewComponent],
  imports: [
    CommonModule,
    SharedModule,
  ],
  exports: [ModalEventActionsViewComponent],
  entryComponents: [ModalEventActionsViewComponent],
})
export class ModalEventActionsViewModule {
}
