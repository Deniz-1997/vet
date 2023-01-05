import {NgModule} from '@angular/core';
import {BreadcrumbsItemComponent} from './breadcrumbs-item.component';
import {CommonModule} from "@angular/common";
import {RouterModule} from "@angular/router";

@NgModule({
  declarations: [
    BreadcrumbsItemComponent
  ],
  imports: [CommonModule, RouterModule],
  exports: [
    BreadcrumbsItemComponent
  ]
})
export class BreadcrumbsItemModule {
}
