import {NgModule} from '@angular/core';
import {ListItemTitleComponent} from './listItemTitle.component';
import {CommonModule} from "@angular/common";


@NgModule({
  declarations: [
    ListItemTitleComponent
  ],
  imports: [CommonModule
  ],
  exports: [
    ListItemTitleComponent
  ]
})
export class ListItemTitleModule {
}
