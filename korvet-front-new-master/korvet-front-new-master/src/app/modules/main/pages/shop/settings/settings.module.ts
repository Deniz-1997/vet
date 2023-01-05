import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { SettingsRoutingModule } from './settings-routing.module';
import {ShopSettingsListComponent} from './shop-settings-list/shop-settings-list.component';
import {ShopSettingsEditComponent} from './shop-settings-edit/shop-settings-edit.component';
import {SharedModule} from '../../../../shared/shared.module';
import {ReferenceModule} from '../../../../shared/modules/reference-edit/reference.module';


@NgModule({
  declarations: [ShopSettingsListComponent, ShopSettingsEditComponent],
    imports: [
        CommonModule,
        SettingsRoutingModule,
        SharedModule,
        ReferenceModule
    ]
})
export class SettingsModule { }
