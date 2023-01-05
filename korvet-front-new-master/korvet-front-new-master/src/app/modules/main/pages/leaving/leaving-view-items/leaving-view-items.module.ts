import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {LeavingViewItemsComponent} from './leaving-view-items.component';
import {SharedModule} from '../../../../shared/shared.module';
import {RouterModule, Routes} from '@angular/router';

const routes: Routes = [];

@NgModule({
  declarations: [LeavingViewItemsComponent],
  imports: [
    CommonModule,
    SharedModule,
    RouterModule.forChild(routes)
  ],
  exports: [
    LeavingViewItemsComponent,
  ],
})
export class LeavingViewItemsModule {
}
