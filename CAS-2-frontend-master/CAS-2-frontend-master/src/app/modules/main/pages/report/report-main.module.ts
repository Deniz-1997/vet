import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ReportComponent} from './report.component';
import {SharedModule} from '../../../shared/shared.module';
import {ReportRoutingModule} from './report-routing.module';

@NgModule({
  declarations: [ReportComponent],
  imports: [
    CommonModule,
    SharedModule,
    ReportRoutingModule,
  ],
})
export class ReportMainModule {
}
