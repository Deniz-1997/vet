import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {MainRoutingModule} from './main-routing.module';
import {MainComponent} from './components/main/main.component';
import {MainNotificationComponent} from './components/main-notification/main-notification.component';
import {MainHeaderComponent} from './components/main-header/main-header.component';
import {SharedModule} from '../shared/shared.module';
import {MainUserMenuComponent} from './components/main-user-menu/main-user-menu.component';
import {NavigatorComponent} from './components/main/navigator/navigator.component';
import {
  BadgeModule,
  BreadcrumbsItemModule,
  BreadcrumbsModule,
  ButtonModule,
  ColModule,
  ContainerModule,
  IconModule, ListItemContentModule, ListItemGroupModule, ListItemIconModule,
  ListItemModule,
  ListItemTitleModule, ListModule, NavigationModule,
  RowModule
} from '@korvet/ui-elements';
import {ReportStatusService} from './pages/report/report-services/report-status.service';

@NgModule({
  declarations: [MainComponent, NavigatorComponent,
    MainNotificationComponent, MainHeaderComponent, MainUserMenuComponent],
  imports: [
    CommonModule,
    MainRoutingModule,
    SharedModule,
    ContainerModule,
    RowModule,
    ColModule,
    ButtonModule,
    BreadcrumbsModule,
    BreadcrumbsItemModule,
    IconModule,
    BadgeModule,
    ListItemModule,
    ListItemTitleModule,
    ListItemContentModule,
    ListModule,
    ListItemIconModule,
    NavigationModule,
    ListItemGroupModule,
  ],
  providers: [ReportStatusService]
})
export class MainModule {
}
