import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {EditComponent} from './edit/edit.component';
import {RoutingModule} from './routing.module';
import {SharedModule} from '../../../../../shared/shared.module';

@NgModule({
  declarations: [EditComponent],
  imports: [
    CommonModule,
    SharedModule,
    RoutingModule
  ]
})
export class MainModule {
}
