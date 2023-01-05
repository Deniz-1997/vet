import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {ReferenceListComponent} from './reference-list.component';
import {SharedModule} from '../../shared.module';
import {RouterModule} from '@angular/router';
import {ViewModule} from './view/view.module';



@NgModule({
  declarations: [ReferenceListComponent],
    imports: [
        CommonModule,
        SharedModule,
        RouterModule,
        ViewModule,
    ],
  exports: [ReferenceListComponent]
})
export class ReferenceModule { }
