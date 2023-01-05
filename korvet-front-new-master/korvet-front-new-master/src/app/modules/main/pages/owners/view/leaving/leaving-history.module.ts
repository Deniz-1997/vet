import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {RouterModule} from '@angular/router';
import {LeavingHistoryComponent} from './leaving-history.component';
import {SharedModule} from '../../../../../shared/shared.module';


@NgModule({
  declarations: [LeavingHistoryComponent],
  imports: [
    CommonModule,
    SharedModule,
    RouterModule,
  ],
  exports: [LeavingHistoryComponent]
})
export class LeavingHistoryModule {
}
