import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {OwnerFileViewComponent} from './owner-file-view.component';
import {SharedModule} from '../../shared.module';

@NgModule({
  declarations: [OwnerFileViewComponent],
  imports: [
    CommonModule,
    SharedModule,
  ],
  exports: [OwnerFileViewComponent],
})
export class OwnerFileViewModule {
}
