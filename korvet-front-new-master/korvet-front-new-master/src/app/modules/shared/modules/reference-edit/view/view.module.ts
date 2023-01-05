import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {RouterModule} from '@angular/router';
import {SharedModule} from '../../../shared.module';
import {ReferenceViewComponent} from './reference-view.component';



@NgModule({
  declarations: [ReferenceViewComponent],
  imports: [
    CommonModule,
    SharedModule,
    RouterModule,
  ],
  exports: [ReferenceViewComponent]
})
export class ViewModule { }
