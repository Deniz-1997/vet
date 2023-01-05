import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { VaccinationComponent } from './vaccination.component';
import {SharedModule} from 'src/app/modules/shared/shared.module';
import {VaccinationRoutingModule} from './vaccination-routing.module';
import {
  ButtonModule,
  ColModule,
  ContainerModule,
  FormsModule,
  HeaderModule,
  IconModule,
  RowModule,
  SubheaderModule,
  TextFieldModule
} from '@korvet/ui-elements';
import {FlexLayoutModule} from '@angular/flex-layout';
import {UniversalMatTableModule} from '../../../../shared/modules/universal-mat-table/universal-mat-table.module';
import {ViewComponent} from './view/view.component';
import {ModalFileErrorEditComponent} from '../../../../shared/components/modal-file-error-edit/modal.component';
import {DataNameService} from './view/data-name.service';
import {ModalVaccinationImportComponent} from './modal-vaccination-import/modal-import.component';



@NgModule({
  declarations: [VaccinationComponent, ViewComponent, ModalFileErrorEditComponent, ModalVaccinationImportComponent],
  imports: [
    CommonModule,
    SharedModule,
    VaccinationRoutingModule,
    ContainerModule,
    RowModule,
    ColModule,
    ButtonModule,
    FlexLayoutModule,
    HeaderModule,
    SubheaderModule,
    UniversalMatTableModule,
    TextFieldModule,
    IconModule,
    FormsModule,
  ],
  providers: [DataNameService]
})
export class VaccinationModule { }
