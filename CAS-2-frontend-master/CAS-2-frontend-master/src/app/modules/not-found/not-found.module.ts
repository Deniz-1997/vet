import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NotFoundRoutingModule } from './not-found-routing.module';
import { NotFoundComponent } from './components/not-found/not-found.component';
import {ColModule, ContainerModule, RowModule} from '@korvet/ui-elements';

@NgModule({
  declarations: [NotFoundComponent],
  imports: [
    CommonModule,
    NotFoundRoutingModule,
    ContainerModule,
    RowModule,
    ColModule
  ]
})
export class NotFoundModule {}
