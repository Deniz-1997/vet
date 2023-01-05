import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ColModule, RowModule} from '@korvet/ui-elements';
import {RoutingModule} from './routing.module';
import {MainComponent} from './main.component';
import {SharedModule} from '../../../shared/shared.module';

@NgModule({
  declarations: [MainComponent],
  exports: [
    MainComponent
  ],
  imports: [
    CommonModule,
    SharedModule,
    RoutingModule,
    ColModule,
    RowModule
  ]
})

export class MainModule {
}
