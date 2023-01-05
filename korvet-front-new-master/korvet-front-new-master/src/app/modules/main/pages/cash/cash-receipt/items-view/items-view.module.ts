import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ItemsViewComponent} from './items-view.component';
import {RouterModule, Routes} from '@angular/router';
import {SharedModule} from '../../../../../shared/shared.module';

const routes: Routes = [];

@NgModule({
  declarations: [ItemsViewComponent],
  imports: [
    CommonModule,
    RouterModule.forChild(routes),
    SharedModule
  ],
  exports: [
    ItemsViewComponent,
  ],
})

export class ItemsViewModule {
}
