import {NgModule} from '@angular/core';
import {BreadcrumbsComponent} from './breadcrumbs.component';
import {CommonModule} from "@angular/common";


@NgModule({
  declarations: [
    BreadcrumbsComponent
  ],
  imports: [CommonModule],
  exports: [
    BreadcrumbsComponent
  ]
})
export class BreadcrumbsModule {
}
