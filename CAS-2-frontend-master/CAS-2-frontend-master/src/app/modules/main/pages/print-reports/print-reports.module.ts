import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {PrintReportsComponent} from './print-reports.component';
import {PrintReportsRoutingModule} from './print-reports-routing.module';
import {SharedModule} from '../../../shared/shared.module';
import {UsersBusinessEntityComponent} from './users-business-entity/users-business-entity.component';
import {ButtonModule, ColModule, HeaderModule, RowModule} from '@korvet/ui-elements';
import {ReportsCountComponent} from './reports-count/reports-count.component';



@NgModule({
  declarations: [PrintReportsComponent, UsersBusinessEntityComponent, ReportsCountComponent],
  imports: [
    CommonModule,
    PrintReportsRoutingModule,
    SharedModule,
    ColModule,
    RowModule,
    ButtonModule,
    HeaderModule,
  ]
})
export class PrintReportsModule { }
