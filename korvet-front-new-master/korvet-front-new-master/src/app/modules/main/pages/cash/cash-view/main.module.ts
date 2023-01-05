import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {ViewComponent} from './view/view.component';
import {EditComponent} from './edit/edit.component';
import {SharedModule} from '../../../../shared/shared.module';

@NgModule({
  declarations: [ViewComponent, EditComponent],
  imports: [
    CommonModule,
    SharedModule,
    RoutingModule
  ]
})
export class MainModule {
}
