import {NgModule} from '@angular/core';
import {ListItemContentComponent} from './listItemContent.component';
import {CommonModule} from "@angular/common";


@NgModule({
  declarations: [
    ListItemContentComponent
  ],
  imports: [CommonModule
  ],
  exports: [
    ListItemContentComponent
  ]
})
export class ListItemContentModule {
}
