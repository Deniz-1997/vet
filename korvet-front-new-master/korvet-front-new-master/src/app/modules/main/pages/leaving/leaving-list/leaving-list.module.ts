import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import {LeavingListRoutingModule} from './leaving-list-routing.module';
import {SharedModule} from '../../../../shared/shared.module';
import {LeavingListComponent} from './leaving-list.component';


@NgModule({
  declarations: [LeavingListComponent],
  imports: [
    CommonModule,
    SharedModule,
    LeavingListRoutingModule
  ]
})
export class LeavingListModule { }
