import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {RoutingModule} from './routing.module';
import {SharedModule} from '../../../../shared/shared.module';
import {MainComponent} from './main.component';
import {UniversalReferenceMenuModule} from '../../../../shared/modules/universal-reference-menu/universal-reference-menu.module';

@NgModule({
  declarations: [MainComponent],
  imports: [
    CommonModule,
    SharedModule,
    RoutingModule,
    UniversalReferenceMenuModule,
  ]
})
export class MainModule {
}
