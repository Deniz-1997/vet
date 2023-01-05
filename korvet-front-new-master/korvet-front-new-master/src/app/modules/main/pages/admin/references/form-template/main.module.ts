import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {RoutingModule} from './routing.module';
import {ListComponent} from './list/list.component';
import {EditComponent} from './edit/edit.component';
import {SharedModule} from '../../../../../shared/shared.module';
import {TextInputHighlightModule} from 'angular-text-input-highlight';
import {ViewComponent} from './view/view.component';
import {DragDropModule} from '@angular/cdk/drag-drop';

@NgModule({
  declarations: [
    ListComponent,
    EditComponent,
    ViewComponent,
  ],
  exports: [
    ViewComponent
  ],
  imports: [
    CommonModule,
    SharedModule,
    RoutingModule,
    DragDropModule,
    TextInputHighlightModule
  ]
})
export class MainModule {
}
