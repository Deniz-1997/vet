import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {ViewRoutingModule} from './view-routing.module';
import {SharedModule} from '../../../../shared/shared.module';
import {HistoryComponent} from './history/history.component';
import {ViewComponent} from './view/view.component';
import {DeathComponent} from './death/death.component';
import {RegisterComponent} from './register/register.component';
import {EditComponent} from './register/edit/edit.component';

@NgModule({
  declarations: [
    HistoryComponent,
    ViewComponent,
    DeathComponent,
    RegisterComponent,
    EditComponent
  ],
  exports: [
    HistoryComponent,
    ViewComponent
  ],
  imports: [
    CommonModule,
    SharedModule,
    ViewRoutingModule,
  ]
})
export class ViewModule {
}
