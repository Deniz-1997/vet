import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FilterComponent } from './filter.component';
import {SharedModule} from 'src/app/modules/shared/shared.module';
import {ButtonModule, ColModule, ContainerModule, HeaderModule, RowModule, SubheaderModule} from '@korvet/ui-elements';
import {ReportFilterService} from '../report-services/report-filter.service';
import {ReportTypeService} from '../report-services/report-type.service';
import {ReportModelService} from '../report-services/report-model.service';
import {ReportStatusService} from '../report-services/report-status.service';
import {TableComponent} from '../table/table.component';
import {ReportFormComponent} from '../report-form/report-form.component';
import {ReportDataService} from '../report-services/report-data.service';
import {FlexLayoutModule} from '@angular/flex-layout';
import {ButtonsComponentComponent} from '../buttons-component/buttons-component.component';
import {ExplanatoryNoteFormModule} from '../../../../shared/modules/explanatory-note-form/explanatory-note-form.module';
import {ExplanatoryNoteService} from '../report-services/explanatory-note.service';
import {HistoryReportFormModule} from '../../../../shared/modules/report-history/history-report-form.module';




@NgModule({
  declarations: [FilterComponent, TableComponent, ReportFormComponent, ButtonsComponentComponent],
    imports: [
        CommonModule,
        SharedModule,
        ContainerModule,
        RowModule,
        ColModule,
        HeaderModule,
        SubheaderModule,
        ButtonModule,
        FlexLayoutModule,
        ExplanatoryNoteFormModule,
        HistoryReportFormModule

    ],
  exports: [
    FilterComponent
  ],
  providers: [ReportFilterService, ReportTypeService, ReportModelService, ReportStatusService, ReportDataService, ExplanatoryNoteService]
})
export class FilterModule { }
