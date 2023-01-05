import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {UniversalReferenceMenuComponent} from './universal-reference-menu.component';
import {SharedModule} from '../../shared.module';
import {RouterModule} from '@angular/router';
import {ColModule, HeaderModule, RowModule} from '@korvet/ui-elements';
import {FlexLayoutModule} from '@angular/flex-layout';



@NgModule({
  declarations: [UniversalReferenceMenuComponent],
  imports: [
    CommonModule,
    SharedModule,
    RouterModule,
    RowModule,
    ColModule,
    HeaderModule,
    FlexLayoutModule,
  ],
  exports: [UniversalReferenceMenuComponent]
})
export class UniversalReferenceMenuModule { }
