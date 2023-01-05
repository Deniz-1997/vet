import {NgModule} from '@angular/core';
import {RoutingModule} from './routing.module';
import {ListComponent} from './list/list.component';
import {FilterModule} from '../../filter/filter.module';



@NgModule({
  declarations: [ListComponent],
  exports: [
    ListComponent
  ],
    imports: [
        RoutingModule,
      FilterModule
    ]
})
export class DisinfectantsModule {
}
