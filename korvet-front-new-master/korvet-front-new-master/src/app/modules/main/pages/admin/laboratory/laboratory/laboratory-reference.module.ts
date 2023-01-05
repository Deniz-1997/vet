import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {RoutingModule} from './routing.module';
import {SharedModule} from 'src/app/modules/shared/shared.module';
import { ListComponent } from './list/list.component';
import { EditComponent } from './edit/edit.component';


@NgModule({
  declarations: [ListComponent, EditComponent],
  imports: [
    CommonModule,
    RoutingModule,
    SharedModule
  ],
  exports: [EditComponent]
})
export class LaboratoryReferenceModule {
  constructor() {
  }
}
