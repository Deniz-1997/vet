import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ViewItemsComponent} from './view-items.component';
import {SharedModule} from '../../../../shared/shared.module';
import {RouterModule, Routes} from '@angular/router';

const routes: Routes = [];

@NgModule({
  declarations: [ViewItemsComponent],
  imports: [
    CommonModule,
    SharedModule,
    RouterModule.forChild(routes)
  ],
  exports: [
    ViewItemsComponent,
  ],
})
export class ViewItemsModule {
}
