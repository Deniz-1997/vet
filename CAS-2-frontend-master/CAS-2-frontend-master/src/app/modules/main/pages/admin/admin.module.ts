import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {AdminRoutingModule} from './admin-routing.module';
import {AdminComponent} from './admin.component';
import {MaintenanceComponent} from './maintenance/maitenence.component';
import {SharedModule} from '../../../shared/shared.module';
import {ColModule, ContainerModule, RowModule} from '@korvet/ui-elements';

@NgModule({
  declarations: [AdminComponent, MaintenanceComponent],
  imports: [CommonModule, AdminRoutingModule, SharedModule, ContainerModule, RowModule, ColModule]
})
export class AdminModule {

}
