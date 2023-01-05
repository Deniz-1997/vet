import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {RouterModule} from '@angular/router';
import {SharedModule} from '../../../shared.module';
import {ReferenceViewComponent} from './reference-view.component';
import {ButtonModule, ColModule, HeaderModule, RowModule, SubheaderModule} from '@korvet/ui-elements';



@NgModule({
  declarations: [ReferenceViewComponent],
  imports: [
    CommonModule,
    SharedModule,
    RouterModule,
    RowModule,
    ColModule,
    SubheaderModule,
    ButtonModule,
    HeaderModule,
  ],
  exports: [ReferenceViewComponent]
})
export class ViewModule { }
