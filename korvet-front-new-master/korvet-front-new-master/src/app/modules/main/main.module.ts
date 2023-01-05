import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {MainRoutingModule} from './main-routing.module';
import {MainComponent} from './components/main/main.component';
import {MainSidenavComponent} from './components/main-sidenav/main-sidenav.component';
import {MainBreadcrumbsComponent} from './components/main-breadcrumbs/main-breadcrumbs.component';
import {MainNotificationComponent} from './components/main-notification/main-notification.component';
import {MainHeaderComponent} from './components/main-header/main-header.component';
import {SharedModule} from '../shared/shared.module';
import {MainUserMenuComponent} from './components/main-user-menu/main-user-menu.component';

@NgModule({
  declarations: [MainComponent, MainSidenavComponent, MainBreadcrumbsComponent,
    MainNotificationComponent, MainHeaderComponent, MainUserMenuComponent],
  imports: [
    CommonModule,
    MainRoutingModule,
    SharedModule,
  ]
})
export class MainModule {
}
