import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { CountriesRoutingModule } from './countries-routing.module';
import { CountriesListComponent } from './countries-list/countries-list.component';
import { CountriesEditComponent } from './countries-edit/countries-edit.component';
import {SharedModule} from '../../../../../shared/shared.module';
import {ReferenceModule} from '../../../../../shared/modules/reference-edit/reference.module';


@NgModule({
  declarations: [CountriesListComponent, CountriesEditComponent],
    imports: [
        CommonModule,
        CountriesRoutingModule,
        SharedModule,
        ReferenceModule,
    ]
})
export class CountriesModule { }
