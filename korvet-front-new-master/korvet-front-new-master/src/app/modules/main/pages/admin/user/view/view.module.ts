import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {RoutingModule} from './routing.module';
import {ViewComponent} from './view.component';
import {SharedModule} from '../../../../../shared/shared.module';
import {ProfileComponent} from './profile/profile.component';


@NgModule({
  declarations: [ViewComponent, ProfileComponent],
  imports: [
    CommonModule,
    RoutingModule,
    SharedModule
  ],
  entryComponents: [],
})
export class ViewModule {
}
