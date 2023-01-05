import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NomenclatureRoutingModule } from './nomenclature-routing.module';
import { NomenclatureListComponent } from './nomenclature-list/nomenclature-list.component';
import {SharedModule} from '../../../../../shared/shared.module';


@NgModule({
  declarations: [NomenclatureListComponent],
  imports: [
    CommonModule,
    NomenclatureRoutingModule,
    SharedModule
  ]
})
export class NomenclatureModule { }
