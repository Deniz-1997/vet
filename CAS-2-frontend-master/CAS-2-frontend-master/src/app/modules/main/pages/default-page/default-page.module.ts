import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { DefaultPageComponent } from './default-page/default-page.component';
import {DefaultPageRoutingModule} from './default-page-routing.module';
import {UniversalReferenceMenuModule} from 'src/app/modules/shared/modules/universal-reference-menu/universal-reference-menu.module';



@NgModule({
  declarations: [
    DefaultPageComponent
  ],
  imports: [
    CommonModule,
    DefaultPageRoutingModule,
    UniversalReferenceMenuModule
  ]
})
export class DefaultPageModule { }
